<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Tries each AI provider in order (Zhipu → Kimi → Gemini).
 * Rotates on rate-limit (429) or auth errors (401/403).
 * Returns the raw chat completion response or throws on total failure.
 */
class AiProviderRotatorService
{
    /** HTTP status codes that trigger a provider rotation */
    private const ROTATE_ON_STATUS = [429, 401, 403];

    /** Zhipu error codes that indicate exhaustion → rotate */
    private const ROTATE_ON_CODES = ['1310', '1301', '1302'];

    public function chatCompletion(array $messages, array $options = []): array
    {
        $providers = config('revenueforge.ai.providers', []);
        $errors = [];

        foreach ($providers as $index => $provider) {
            // Skip providers with no key configured
            if (empty($provider['api_key'])) {
                $errors[] = "[{$provider['name']}] No API key configured — skipping.";
                continue;
            }

            $result = $this->tryProvider($provider, $messages, $options);

            if ($result['success']) {
                Log::info('AI provider used', ['provider' => $provider['name']]);
                return $result;
            }

            $errors[] = "[{$provider['name']}] {$result['error']}";
            Log::warning('AI provider failed, rotating', [
                'provider' => $provider['name'],
                'error'    => $result['error'],
            ]);
        }

        // All providers exhausted
        $errorMessage = implode(' | ', $errors);
        Log::error('All AI providers exhausted', ['errors' => $errors]);

        return [
            'success'    => false,
            'all_failed' => true,
            'error'      => 'AI tidak tersedia saat ini. Semua provider sudah mencapai limit atau tidak terkonfigurasi. Detail: ' . $errorMessage,
            'model_used' => 'none',
            'tokens_used'=> 0,
        ];
    }

    private function tryProvider(array $provider, array $messages, array $options): array
    {
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $provider['api_key'],
                'Content-Type'  => 'application/json',
            ])
            ->withoutVerifying()
            ->timeout(60)
            ->post(rtrim($provider['base_url'], '/') . '/chat/completions', [
                'model'       => $provider['model'],
                'messages'    => $messages,
                'max_tokens'  => $options['max_tokens'] ?? $provider['max_tokens'],
                'temperature' => $options['temperature'] ?? $provider['temperature'],
            ]);

            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            // Rotate on auth / rate-limit status codes
            if (in_array($response->status(), self::ROTATE_ON_STATUS)) {
                return ['success' => false, 'error' => "HTTP {$response->status()} " . $this->extractErrorMessage($response)];
            }

            if (!$response->successful()) {
                return ['success' => false, 'error' => "HTTP {$response->status()} " . $this->extractErrorMessage($response)];
            }

            $body    = $response->json();
            $content = $body['choices'][0]['message']['content'] ?? '';

            return [
                'success'     => true,
                'content'     => $content,
                'model_used'  => $body['model'] ?? $provider['model'],
                'tokens_used' => $body['usage']['total_tokens'] ?? null,
                'duration_ms' => $durationMs,
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function extractErrorMessage(\Illuminate\Http\Client\Response $response): string
    {
        $body = $response->json();

        if (is_array($body)) {
            // Handle Gemini-style array format: [{"error": {"message": "..."}}]
            if (isset($body[0]['error']['message'])) {
                $code = $body[0]['error']['code'] ?? '';
                $msg  = $body[0]['error']['message'];
                // Clean up long Gemini messages with newlines
                $msg = explode("\n", $msg)[0];
                return $code ? "[{$code}]: {$msg}" : ": {$msg}";
            }
            
            // Handle standard openai-compatible format: {"error": {"message": "..."}}
            if (isset($body['error']['message'])) {
                $code = $body['error']['code'] ?? '';
                $msg  = $body['error']['message'];
                return $code ? "[{$code}]: {$msg}" : ": {$msg}";
            }
        }

        // Fallback for unknown formats, truncate if too long
        $raw = trim($response->body());
        if (strlen($raw) > 150) {
            return ': ' . substr($raw, 0, 150) . '... (truncated)';
        }
        
        return $raw ? ": {$raw}" : ': Unknown error';
    }
}
