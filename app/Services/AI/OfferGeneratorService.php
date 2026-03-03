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
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('revenueforge.ai.api_key'),
                'Content-Type' => 'application/json',
            ])->timeout(60)->post(config('revenueforge.ai.base_url') . '/chat/completions', [
                'model' => config('revenueforge.ai.model'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional offer strategist. Always respond with valid JSON only.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => (int) config('revenueforge.ai.max_tokens', 2000),
                'temperature' => (float) config('revenueforge.ai.temperature', 0.7),
            ]);

            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            if (! $response->successful()) {
                Log::error('AI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return $this->fallbackResponse($input, $durationMs);
            }

            $body = $response->json();
            $content = $body['choices'][0]['message']['content'] ?? '';
            $tokensUsed = $body['usage']['total_tokens'] ?? null;
            $modelUsed = $body['model'] ?? config('revenueforge.ai.model');

            // Clean and parse JSON (strip markdown fences if present)
            $content = preg_replace('/^```json\s*|```$/m', '', trim($content));
            $parsed = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('AI response JSON parse failed', ['content' => $content]);
                return $this->fallbackResponse($input, $durationMs);
            }

            return [
                'success' => true,
                'data' => $parsed,
                'model_used' => $modelUsed,
                'tokens_used' => $tokensUsed,
                'duration_ms' => $durationMs,
            ];
        } catch (\Exception $e) {
            Log::error('AI Generation Exception', ['error' => $e->getMessage()]);
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            return $this->fallbackResponse($input, $durationMs);
        }
    }

    private function fallbackResponse(array $input, int $durationMs): array
    {
        return [
            'success' => false,
            'data' => [
                'title' => 'Your Amazing Offer',
                'tagline' => 'Transform your business today',
                'short_pitch' => 'A compelling offer designed for ' . ($input['target_audience'] ?? 'your audience') . '.',
                'long_copy' => '<p>This is a placeholder. Please configure your AI API key to generate real copy.</p>',
                'benefits' => ['Benefit 1', 'Benefit 2', 'Benefit 3'],
                'faq' => [
                    ['question' => 'What is included?', 'answer' => 'Everything you need to get started.'],
                ],
                'pricing_suggestion' => $input['price_range'] ?? 'Contact for pricing',
                'upsell_idea' => 'Consider adding a premium tier',
                'cta' => 'Get Started Now',
            ],
            'model_used' => 'fallback',
            'tokens_used' => 0,
            'duration_ms' => $durationMs,
        ];
    }
}
