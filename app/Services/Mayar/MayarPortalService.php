<?php

namespace App\Services\Mayar;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MayarPortalService
{
    public function __construct(private MayarApiClient $client) {}

    public function generatePortalLink(string $email): array
    {
        if (config('mayar.mock_checkout') || ! config('mayar.api_key')) {
            // Mock mode: generate a local simulation link
            $mockUrl = url('/portal/mock?email=' . urlencode($email));
            return ['success' => true, 'portal_url' => $mockUrl];
        }

        try {
            // Assume Mayar has a /customer/portal endpoint. If not documented perfectly, this is a standard REST approach
            $result = $this->client->post('customer/portal', [
                'email' => $email,
            ]);

            if ($result['success']) {
                $portalUrl = $result['data']['portal_url'] ?? $result['data']['data']['portal_url'] ?? null;
                return ['success' => true, 'portal_url' => $portalUrl];
            }

            return ['success' => false, 'error' => $result['error'] ?? 'Unknown API error'];
        } catch (\Exception $e) {
            Log::error('MayarPortalService generatePortalLink error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
