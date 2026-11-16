<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Midtrans\MidtransSnapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransSnapService $midtrans): JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans webhook received', ['payload' => $payload]);

        $orderId      = $payload['order_id']      ?? '';
        $statusCode   = $payload['status_code']   ?? '';
        $grossAmount  = $payload['gross_amount']  ?? '';
        $signature    = $payload['signature_key'] ?? '';
        $transStatus  = $payload['transaction_status'] ?? '';
        $transId      = $payload['transaction_id'] ?? '';
        $paymentType  = $payload['payment_type']  ?? '';

        // Verify signature
        if (! $midtrans->verifySignature($orderId, $statusCode, $grossAmount, $signature)) {
            Log::warning('Midtrans webhook signature mismatch', compact('orderId'));
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Find the order by matching midtrans order_id stored in the snap token column
        // Format stored: "{snap_token}|{midtrans_order_id}" or "__pending__:{midtrans_order_id}"
        $order = Order::where('midtrans_snap_token', 'like', '%|' . $orderId)
            ->orWhere('midtrans_snap_token', 'like', '__pending__:' . $orderId)
            ->first();

        if (! $order) {
            Log::warning('Midtrans webhook: order not found', ['midtrans_order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Idempotency — skip if already processed in final state
        if (in_array($order->status?->value ?? $order->status, ['paid', 'failed'])) {
            return response()->json(['message' => 'Already processed']);
        }

        // Update midtrans_transaction_id
        $order->update(['midtrans_transaction_id' => $transId]);

        if (in_array($transStatus, ['settlement', 'capture'])) {
            // Payment successful
            $order->markPaid();

            Payment::create([
                'order_id'       => $order->id,
                'status'         => 'paid',
                'amount'         => $order->amount,
                'currency'       => $order->currency,
                'payment_method' => $paymentType,
                'paid_at'        => now(),
                'metadata'       => $payload,
            ]);

            Log::info('Midtrans: order marked paid', ['order_id' => $order->id]);

        } elseif (in_array($transStatus, ['cancel', 'expire', 'deny', 'failure'])) {
            // Payment failed
            $order->markFailed();

            Payment::create([
                'order_id'       => $order->id,
                'status'         => 'failed',
                'amount'         => $order->amount,
                'currency'       => $order->currency,
                'payment_method' => $paymentType,
                'failed_at'      => now(),
                'metadata'       => $payload,
            ]);

            Log::info('Midtrans: order marked failed', ['order_id' => $order->id, 'status' => $transStatus]);
        }

        return response()->json(['message' => 'OK']);
    }
}
