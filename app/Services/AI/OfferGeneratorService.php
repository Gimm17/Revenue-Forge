<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Log;

class OfferGeneratorService
{
    public function __construct(
        private PromptBuilderService $promptBuilder,
        private AiProviderRotatorService $rotator,
    ) {}

    public function generate(array $input): array
    {
        $prompt = $this->promptBuilder->buildOfferPrompt($input);

        $messages = [
            [
                'role'    => 'system',
                'content' => 'You are a professional offer strategist. Always respond with valid JSON only.',
            ],
            [
                'role'    => 'user',
                'content' => $prompt,
            ],
        ];

        $result = $this->rotator->chatCompletion($messages);

        if (!$result['success']) {
            return [
                'success'     => false,
                'error'       => $result['error'],
                'all_failed'  => $result['all_failed'] ?? false,
                'model_used'  => 'none',
                'tokens_used' => 0,
                'duration_ms' => 0,
            ];
        }

        // Clean and parse JSON (strip markdown fences if present)
        $content = preg_replace('/^```json\s*|```$/m', '', trim($result['content']));
        $parsed  = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('AI response JSON parse failed', ['content' => $content]);
            return [
                'success'     => false,
                'error'       => 'AI merespons tapi gagal di-parse sebagai JSON. Coba lagi.',
                'model_used'  => $result['model_used'],
                'tokens_used' => $result['tokens_used'] ?? 0,
                'duration_ms' => $result['duration_ms'] ?? 0,
            ];
        }

        return [
            'success'     => true,
            'data'        => $parsed,
            'model_used'  => $result['model_used'],
            'tokens_used' => $result['tokens_used'] ?? 0,
            'duration_ms' => $result['duration_ms'] ?? 0,
        ];
    }
}
