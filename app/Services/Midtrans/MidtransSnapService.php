<?php

namespace App\Services\Midtrans;

use App\Models\Offer;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransSnapService
{
    private string $serverKey;
    private string $apiUrl;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key', '');
        $this->apiUrl    = config('midtrans.api_url', 'https://api.sandbox.midtrans.com');
    }

    /**
     * Create a Snap transaction token for the given order.
     * Returns ['success' => true, 'token' => '...'] or ['success' => false, 'error' => '...']
     */
    public function createSnapToken(Order $order, Offer $offer, array $customerData): array
    {
        if (empty($this->serverKey) || str_contains($this->serverKey, 'XXXX')) {
            return ['success' => false, 'error' => 'Midtrans server key belum dikonfigurasi. Silakan isi MIDTRANS_SERVER_KEY di .env'];
        }

        $orderId = 'RF-' . $order->id . '-' . time();

        // Store the midtrans order id prefix on the order so we can look it up on webhook
        $order->update(['midtrans_snap_token' => '__pending__:' . $orderId]);

        $payload = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $offer->price,
            ],
            'customer_details' => [
                'first_name' => $customerData['name'] ?? 'Customer',
                'email'      => $customerData['email'],
                'phone'      => $customerData['phone'] ?? null,
            ],
            'item_details' => [
                [
                    'id'       => (string) $offer->id,
                    'price'    => (int) $offer->price,
                    'quantity' => 1,
                    'name'     => mb_substr($offer->title, 0, 50),
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.success'),
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->timeout(30)
                ->post($this->apiUrl . '/snap/v1/transactions', $payload);

            if ($response->successful()) {
                $data  = $response->json();
                $token = $data['token'] ?? null;

                if ($token) {
                    // Store the real snap token along with the midtrans order_id
                    $order->update(['midtrans_snap_token' => $token . '|' . $orderId]);
                    return ['success' => true, 'token' => $token, 'redirect_url' => $data['redirect_url'] ?? null];
                }

                return ['success' => false, 'error' => 'Tidak mendapat token dari Midtrans.'];
            }

            $error = $response->json()['status_message'] ?? $response->body();
            Log::error('Midtrans Snap error', ['status' => $response->status(), 'body' => $error]);
            return ['success' => false, 'error' => $error];

        } catch (\Exception $e) {
            Log::error('Midtrans Snap exception', ['message' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Gagal menghubungi Midtrans: ' . $e->getMessage()];
        }
    }

    /**
     * Verify webhook signature from Midtrans.
     * Signature = SHA512(order_id + status_code + gross_amount + server_key)
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $receivedSignature): bool
    {
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);
        return hash_equals($expected, $receivedSignature);
    }
}
