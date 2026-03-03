<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OfferGeneratorService
{
    public function __construct(
        private PromptBuilderService $promptBuilder,
    ) {}

    public function generate(array $input): array
    {
        $prompt = $this->promptBuilder->buildOfferPrompt($input);
        $config = config('revenueforge.ai');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $config['api_key'],
                'Content-Type' => 'application/json',
            ])
            ->withoutVerifying()
            ->timeout(60)
            ->post(rtrim($config['base_url'], '/') . '/chat/completions', [
                'model' => $config['model'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional offer strategist. Always respond with valid JSON only. No markdown, no extra text.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => (int) $config['max_tokens'],
                'temperature' => (float) $config['temperature'],
                'response_format' => ['type' => 'json_object'],
            ]);

            if (!$response->successful()) {
                $body = $response->json();
                $errorMsg = $body['error']['message'] ?? $response->body();
                // Truncate long error messages
                if (strlen($errorMsg) > 200) {
                    $errorMsg = substr($errorMsg, 0, 200) . '...';
                }
                Log::warning('AI API error', ['status' => $response->status(), 'error' => $errorMsg]);
                return [
                    'success' => false,
                    'error' => "AI Error ({$response->status()}): {$errorMsg}",
                    'model_used' => 'error',
                    'tokens_used' => 0,
                    'duration_ms' => 0,
                ];
            }

            $body = $response->json();
            $content = $body['choices'][0]['message']['content'] ?? '';

            // Extract JSON object from response
            $content = trim($content);
            if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
                $content = $matches[0];
            }

            $parsed = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI response JSON parse failed', ['content' => substr($content, 0, 500)]);
                return [
                    'success' => false,
                    'error' => 'AI merespons tapi gagal di-parse sebagai JSON. Coba lagi.',
                    'model_used' => $body['model'] ?? $config['model'],
                    'tokens_used' => $body['usage']['total_tokens'] ?? 0,
                    'duration_ms' => 0,
                ];
            }

            return [
                'success' => true,
                'data' => $parsed,
                'model_used' => $body['model'] ?? $config['model'],
                'tokens_used' => $body['usage']['total_tokens'] ?? 0,
                'duration_ms' => 0,
            ];
        } catch (\Exception $e) {
            Log::error('AI generation exception', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'AI generation failed: ' . $e->getMessage(),
                'model_used' => 'error',
                'tokens_used' => 0,
                'duration_ms' => 0,
            ];
        }
    }
}
