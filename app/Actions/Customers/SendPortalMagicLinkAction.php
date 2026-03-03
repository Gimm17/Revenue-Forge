<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use App\Models\PortalSessionLog;
use App\Services\Mayar\MayarPortalService;

class SendPortalMagicLinkAction
{
    public function __construct(private MayarPortalService $portalService) {}

    public function execute(Customer $customer): array
    {
        $result = $this->portalService->generatePortalLink($customer->email);

        if ($result['success']) {
            PortalSessionLog::create([
                'customer_id' => $customer->id,
                'type' => 'magic_link',
                'status' => 'sent',
                'sent_at' => now(),
                'metadata' => ['portal_url' => $result['portal_url']]
            ]);
            
            return ['success' => true, 'message' => 'Portal link sent successfully!'];
        }

        return ['success' => false, 'message' => $result['error'] ?? 'Failed to generate portal link.'];
    }
}
