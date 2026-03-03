<?php

namespace App\Actions\AI;

use App\Models\AiGeneration;
use App\Models\Offer;
use App\Models\OfferVersion;
use App\Services\AI\OfferGeneratorService;

class GenerateOfferAction
{
    public function __construct(
        private OfferGeneratorService $generator,
    ) {}

    public function execute(array $input, int $workspaceId, int $userId, ?Offer $offer = null): array
    {
        $result = $this->generator->generate($input);

        // Log AI generation
        $generation = AiGeneration::create([
            'workspace_id' => $workspaceId,
            'offer_id' => $offer?->id,
            'user_id' => $userId,
            'input_params' => $input,
            'output_content' => $result['data'] ?? [],
            'model_used' => $result['model_used'],
            'tokens_used' => $result['tokens_used'],
            'duration_ms' => $result['duration_ms'],
        ]);

        // Store version if linked to an offer
        if ($offer) {
            $latestVersion = $offer->versions()->max('version') ?? 0;
            OfferVersion::create([
                'offer_id' => $offer->id,
                'version' => $latestVersion + 1,
                'content' => $result['data'],
                'generated_by' => 'ai',
            ]);
        }

        return [
            'success' => $result['success'],
            'data' => $result['data'] ?? null,
            'error' => $result['error'] ?? null,
            'generation_id' => $generation->id ?? null,
        ];
    }
}
