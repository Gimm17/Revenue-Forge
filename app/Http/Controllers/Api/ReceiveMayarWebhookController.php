<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessMayarWebhookJob;
use App\Services\Mayar\MayarApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReceiveMayarWebhookController extends Controller
{
    public function __invoke(Request $request, MayarApiClient $client): JsonResponse
    {
        // Verify signature (skip in sandbox/testing)
        if (config('mayar.mode') !== 'sandbox') {
            $signature = $request->header('X-Mayar-Signature', '');
            if (! $client->verifyWebhookSignature($request->getContent(), $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $payload = $request->all();
        $eventType = $payload['event'] ?? $payload['type'] ?? 'unknown';

        ProcessMayarWebhookJob::dispatch($payload, $eventType);

        return response()->json(['status' => 'ok']);
    }
}
