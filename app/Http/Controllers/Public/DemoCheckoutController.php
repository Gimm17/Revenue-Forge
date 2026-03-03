<?php

namespace App\Http\Controllers\Public;

use App\Jobs\ProcessMayarWebhookJob;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class DemoCheckoutController extends Controller
{
    public function show(Order $order)
    {
        // This is a mock checkout page for sandbox/demo testing
        // It's only reachable if no Mayar API key is provided and MayarCheckoutService returns a mock URL
        
        $order->load('offer');
        
        return Inertia::render('Public/DemoCheckout', [
            'order' => [
                'id' => $order->id,
                'amount' => $order->amount,
                'formattedAmount' => $order->formattedAmount(),
            ],
            'offer' => [
                'title' => $order->offer->title,
                'type' => $order->offer->type,
            ],
            'customerEmail' => $order->customer->email,
        ]);
    }

    public function process(Request $request, Order $order)
    {
        // Simulate a successful payment webhook from Mayar
        
        $mockTransactionId = 'trx_mock_' . Str::random(10);
        
        $payload = [
            'status' => 'SUCCESS', // Mayar uses SUCCESS
            'id' => $mockTransactionId,
            'amount' => $order->amount,
            'customer' => [
                'email' => $order->customer->email,
            ],
            'metadata' => array_merge([
                'order_id' => $order->id,
                'offer_id' => $order->offer_id,
                'workspace_id' => $order->workspace_id,
            ], $order->metadata ?? [])
        ];
        
        // Dispatch the asynchronous Webhook Job
        ProcessMayarWebhookJob::dispatch($payload, 'payment.success');
        
        return redirect()->route('checkout.success');
    }
}
