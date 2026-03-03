<?php

namespace App\Services\Mayar;

use App\Models\Offer;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Str;

class MayarCheckoutService
{
    public function __construct(private MayarApiClient $client) {}

    public function createCheckout(Offer $offer, array $customerData): array
    {
        $workspace = $offer->workspace;

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['workspace_id' => $workspace->id, 'email' => $customerData['email']],
            ['name' => $customerData['name'] ?? 'Customer', 'phone' => $customerData['phone'] ?? null]
        );

        // Create order record (pending)
        $order = Order::create([
            'workspace_id' => $workspace->id,
            'offer_id' => $offer->id,
            'customer_id' => $customer->id,
            'type' => 'offer_purchase',
            'status' => 'pending',
            'amount' => $offer->price,
            'currency' => $offer->currency,
            'metadata' => !empty($customerData['affiliate_code']) ? ['affiliate_code' => $customerData['affiliate_code']] : null,
        ]);

        // Call Mayar API to create a checkout link
        if (config('mayar.mock_checkout') || ! config('mayar.api_key')) {
            // Sandbox/demo mode: return mock checkout URL
            $checkoutUrl = url("/checkout/demo/{$order->id}");
            $order->update(['mayar_checkout_url' => $checkoutUrl]);

            return ['success' => true, 'checkout_url' => $checkoutUrl, 'order_id' => $order->id];
        }

        $result = $this->client->post('checkout/create', [
            'name' => $offer->title,
            'amount' => $offer->price,
            'description' => $offer->short_pitch ?? $offer->tagline ?? '',
            'customer_email' => $customer->email,
            'customer_name' => $customer->name,
            'callback_url' => route('app.dashboard'),
            'metadata' => [
                'order_id' => $order->id,
                'offer_id' => $offer->id,
                'workspace_id' => $workspace->id,
            ],
        ]);

        if ($result['success']) {
            $checkoutUrl = $result['data']['checkout_url'] ?? $result['data']['data']['checkout_url'] ?? null;
            $transactionId = $result['data']['transaction_id'] ?? $result['data']['data']['id'] ?? null;

            $order->update([
                'mayar_checkout_url' => $checkoutUrl,
                'mayar_transaction_id' => $transactionId,
            ]);

            return ['success' => true, 'checkout_url' => $checkoutUrl, 'order_id' => $order->id];
        }

        $order->update(['status' => 'failed']);
        return ['success' => false, 'error' => $result['error'] ?? 'Failed to create checkout'];
    }
}
