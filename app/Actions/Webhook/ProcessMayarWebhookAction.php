<?php

namespace App\Actions\Webhook;

use App\Models\CustomerAccess;
use App\Models\CreditWallet;
use App\Models\InAppNotification;
use App\Models\MayarWebhookLog;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use App\Mail\DigitalDeliveryEmail;
use App\Mail\NewOrderNotification;
use App\Mail\OrderReceiptEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessMayarWebhookAction
{
    public function execute(array $payload, string $eventType): void
    {
        $eventId = $payload['id'] ?? null;

        if ($eventId) {
            $existingLog = MayarWebhookLog::where('event_type', $eventType)
                ->where('event_id', $eventId)
                ->where('status', 'processed')
                ->exists();

            if ($existingLog) {
                Log::info("Webhook idempotency check: already processed {$eventType} - {$eventId}");
                return;
            }
        }

        $log = MayarWebhookLog::create([
            'event_type' => $eventType,
            'event_id' => $eventId,
            'payload' => $payload,
            'status' => 'processing',
        ]);

        try {
            match ($eventType) {
                'payment.success', 'payment.completed' => $this->handlePaymentSuccess($payload),
                'payment.failed' => $this->handlePaymentFailed($payload),
                'subscription.active' => $this->handleSubscriptionActive($payload),
                'subscription.cancelled' => $this->handleSubscriptionCancelled($payload),
                default => Log::info("Unhandled Mayar webhook: {$eventType}"),
            };

            $log->markProcessed();
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage(), 'event' => $eventType]);
            $log->markFailed($e->getMessage());
            throw $e; // Re-throw so the Laravel Queue knows the job failed and can retry
        }
    }

    private function handlePaymentSuccess(array $payload): void
    {
        $transactionId = $payload['transaction_id'] ?? $payload['data']['transaction_id'] ?? null;
        $orderId = $payload['metadata']['order_id'] ?? null;

        $order = $orderId
            ? Order::find($orderId)
            : ($transactionId ? Order::where('mayar_transaction_id', $transactionId)->first() : null);

        if (! $order || $order->isPaid()) return;

        // Mark order paid
        $order->markPaid();

        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'status' => 'paid',
            'amount' => $order->amount,
            'currency' => $order->currency,
            'mayar_payment_id' => $payload['payment_id'] ?? null,
            'payment_method' => $payload['payment_method'] ?? 'mayar',
            'paid_at' => now(),
            'metadata' => $payload,
        ]);

        // Grant access
        if ($order->customer_id && $order->offer_id) {
            CustomerAccess::create([
                'customer_id' => $order->customer_id,
                'offer_id' => $order->offer_id,
                'order_id' => $order->id,
                'status' => 'active',
                'granted_at' => now(),
            ]);

            // If credit pack, add credits
            $offer = $order->offer;
            if ($offer && $offer->isCreditPack() && $offer->credit_amount) {
                $wallet = CreditWallet::firstOrCreate(
                    ['workspace_id' => $order->workspace_id, 'customer_id' => $order->customer_id],
                    ['balance' => 0],
                );
                $wallet->addCredits($offer->credit_amount, "Purchased: {$offer->title}", 'order', $order->id);
            }

            // If subscription, create subscription record
            if ($offer && $offer->isSubscription()) {
                Subscription::create([
                    'workspace_id' => $order->workspace_id,
                    'customer_id' => $order->customer_id,
                    'offer_id' => $offer->id,
                    'status' => 'active',
                    'mayar_subscription_id' => $payload['subscription_id'] ?? null,
                    'current_period_start' => now(),
                    'current_period_end' => now()->addMonth(),
                ]);
            }
        }

        // Process Affiliate Conversion if exists
        $affiliateCode = $order->metadata['affiliate_code'] ?? null;
        if ($affiliateCode) {
            $link = \App\Models\AffiliateLink::where('code', $affiliateCode)->with('program')->first();
            if ($link && $link->program && $link->program->is_active) {
                $program = $link->program;
                
                $commissionAmount = $program->commission_type === 'percentage' 
                    ? (int) ($order->amount * ($program->commission_value / 100))
                    : (int) $program->commission_value;
                
                \App\Models\AffiliateConversion::create([
                    'link_id' => $link->id,
                    'order_id' => $order->id,
                    'commission_amount' => $commissionAmount,
                    'status' => 'approved',
                ]);
                
                // Track revenue in daily metrics
                \App\Models\DailyMetric::firstOrCreate(
                    ['workspace_id' => $order->workspace_id, 'date' => now()->toDateString()],
                    ['gross_revenue' => 0, 'paid_orders' => 0, 'new_customers' => 0, 'active_subscriptions' => 0, 'credits_sold' => 0, 'affiliate_revenue' => 0]
                )->increment('affiliate_revenue', $commissionAmount);
            }
        }

        // === Send Email Notifications ===
        $order->load(['offer', 'customer', 'workspace.owner']);

        // Receipt to buyer
        if ($order->customer?->email) {
            Mail::to($order->customer->email)->queue(new OrderReceiptEmail($order));
        }

        // Notification to seller
        if ($order->workspace?->owner?->email) {
            Mail::to($order->workspace->owner->email)->queue(new NewOrderNotification($order));
        }

        // === Digital Product Delivery ===
        $offer = $order->offer;
        if ($offer && $offer->delivery_type && $offer->delivery_type !== 'none' && $order->customer?->email) {
            Mail::to($order->customer->email)->queue(new DigitalDeliveryEmail($order));
            Log::info('Digital delivery email queued', ['order_id' => $order->id, 'delivery_type' => $offer->delivery_type]);
        }

        // === In-App Notification for Seller ===
        InAppNotification::create([
            'workspace_id' => $order->workspace_id,
            'type' => 'new_order',
            'title' => 'New Order Received!',
            'body' => ($order->customer?->name ?? $order->customer?->email ?? 'Someone') . ' purchased ' . ($offer?->title ?? 'an offer'),
            'icon' => '💰',
            'data' => [
                'order_id' => $order->id,
                'offer_title' => $offer?->title,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'customer_name' => $order->customer?->name,
            ],
        ]);
    }

    private function handlePaymentFailed(array $payload): void
    {
        $orderId = $payload['metadata']['order_id'] ?? null;
        $order = $orderId ? Order::find($orderId) : null;

        if ($order) {
            $order->markFailed();
            Payment::create([
                'order_id' => $order->id,
                'status' => 'failed',
                'amount' => $order->amount,
                'currency' => $order->currency,
                'failed_at' => now(),
                'metadata' => $payload,
            ]);
        }
    }

    private function handleSubscriptionActive(array $payload): void
    {
        $subId = $payload['subscription_id'] ?? null;
        if (! $subId) return;

        $subscription = Subscription::where('mayar_subscription_id', $subId)->first();
        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]);
        }
    }

    private function handleSubscriptionCancelled(array $payload): void
    {
        $subId = $payload['subscription_id'] ?? null;
        if (! $subId) return;

        $subscription = Subscription::where('mayar_subscription_id', $subId)->first();
        if ($subscription) {
            $subscription->cancel();
        }
    }
}
