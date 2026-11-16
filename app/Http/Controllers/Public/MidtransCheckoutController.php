<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DailyMetric;
use App\Models\Offer;
use App\Models\Order;
use App\Services\Midtrans\MidtransSnapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransCheckoutController extends Controller
{
    public function __invoke(
        Request $request,
        string $slug,
        MidtransSnapService $midtrans
    ): JsonResponse {
        $offer = Offer::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'affiliate_code' => 'nullable|string',
        ]);

        // Read affiliate cookie if not provided
        if (empty($validated['affiliate_code']) && $request->hasCookie('revenueforge_affiliate_ref')) {
            $validated['affiliate_code'] = $request->cookie('revenueforge_affiliate_ref');
        }

        $workspace = $offer->workspace;

        // Find or create customer
        $customer = Customer::firstOrCreate(
            ['workspace_id' => $workspace->id, 'email' => $validated['email']],
            ['name' => $validated['name'], 'phone' => $validated['phone'] ?? null]
        );

        // Create order record (pending)
        $order = Order::create([
            'workspace_id' => $workspace->id,
            'offer_id'     => $offer->id,
            'customer_id'  => $customer->id,
            'type'         => 'offer_purchase',
            'status'       => 'pending',
            'amount'       => $offer->price,
            'currency'     => $offer->currency,
            'metadata'     => !empty($validated['affiliate_code'])
                ? ['affiliate_code' => $validated['affiliate_code']]
                : null,
        ]);

        // Track checkout started metric (silent)
        try {
            DailyMetric::firstOrCreate(
                ['workspace_id' => $workspace->id, 'date' => now()->toDateString()],
                ['gross_revenue' => 0, 'paid_orders' => 0, 'new_customers' => 0, 'active_subscriptions' => 0, 'credits_sold' => 0, 'affiliate_revenue' => 0, 'page_views' => 0, 'checkouts_started' => 0]
            )->increment('checkouts_started');
        } catch (\Exception) {}

        // Get Midtrans Snap token
        $result = $midtrans->createSnapToken($order, $offer, $validated);

        if ($result['success']) {
            return response()->json([
                'success'      => true,
                'snap_token'   => $result['token'],
                'redirect_url' => $result['redirect_url'] ?? null,
                'order_id'     => $order->id,
            ]);
        }

        $order->update(['status' => 'failed']);

        return response()->json([
            'success' => false,
            'error'   => $result['error'] ?? 'Checkout gagal. Silakan coba lagi.',
        ], 422);
    }
}
