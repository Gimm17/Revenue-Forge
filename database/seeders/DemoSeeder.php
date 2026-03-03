<?php

namespace Database\Seeders;

use App\Data\Enums\OfferType;
use App\Models\AffiliateProgram;
use App\Models\Customer;
use App\Models\CustomerAccess;
use App\Models\CreditWallet;
use App\Models\DailyMetric;
use App\Models\Offer;
use App\Models\OfferVersion;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Models\WorkspacePlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── Plans ──
        $freePlan = WorkspacePlan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'price' => 0,
            'interval' => 'monthly',
            'features' => ['1 Workspace', '3 Offers', 'AI Offer Generator', 'Mayar Checkout', 'Basic Analytics'],
            'sort_order' => 0,
        ]);

        $proPlan = WorkspacePlan::create([
            'name' => 'Pro Creator',
            'slug' => 'pro-creator',
            'price' => 149000,
            'interval' => 'monthly',
            'features' => ['Unlimited Offers', 'AI Offer Studio Pro', 'Customer CRM', 'Affiliate Tracking', 'Advanced Analytics', 'Priority Support'],
            'sort_order' => 1,
        ]);

        $bizPlan = WorkspacePlan::create([
            'name' => 'Business',
            'slug' => 'business',
            'price' => 499000,
            'interval' => 'monthly',
            'features' => ['Everything in Pro', 'Team Members', 'White-label Portal', 'API Access', 'Custom Webhooks', 'Dedicated Support'],
            'sort_order' => 2,
        ]);

        // ── Demo User ──
        $user = User::create([
            'name' => 'Demo Creator',
            'email' => 'demo@revenueforge.id',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $workspace = Workspace::create([
            'owner_id' => $user->id,
            'name' => 'RevenueForge Demo',
            'slug' => 'revenueforge-demo',
            'brand_color' => '#06b6d4',
            'is_active' => true,
            'plan_id' => $proPlan->id,
            'plan_status' => 'active',
        ]);

        WorkspaceMember::create(['workspace_id' => $workspace->id, 'user_id' => $user->id, 'role' => 'owner']);
        $user->update(['current_workspace_id' => $workspace->id]);

        // ── Offers ──
        $offers = [
            ['type' => 'one_time', 'title' => 'Landing Page Template Kit', 'tagline' => 'Launch faster', 'price' => 149000, 'is_published' => true, 'published_at' => now()->subDays(20)],
            ['type' => 'subscription', 'title' => 'Creator Membership Premium', 'tagline' => 'Access all resources', 'price' => 99000, 'interval' => 'monthly', 'is_published' => true, 'published_at' => now()->subDays(15)],
            ['type' => 'credit_pack', 'title' => '100 AI Credits Pack', 'tagline' => 'Power your AI workflow', 'price' => 79000, 'credit_amount' => 100, 'is_published' => true, 'published_at' => now()->subDays(10)],
            ['type' => 'one_time', 'title' => 'Business Plan Canvas Workbook', 'tagline' => 'Plan like a pro', 'price' => 49000, 'is_published' => false],
        ];

        $createdOffers = [];
        foreach ($offers as $offerData) {
            $offer = Offer::create([
                'workspace_id' => $workspace->id,
                'slug' => Str::slug($offerData['title']) . '-' . Str::random(4),
                'short_pitch' => 'A premium digital product for creators.',
                'benefits' => ['Save hours of work', 'Professional quality', 'Instant delivery', 'Lifetime access'],
                'faq' => [['question' => 'How do I get access?', 'answer' => 'Instant delivery after payment.']],
                'cta_text' => 'Get Access Now',
                'currency' => 'IDR',
                ...$offerData,
            ]);
            OfferVersion::create(['offer_id' => $offer->id, 'version' => 1, 'content' => $offerData, 'generated_by' => 'manual']);
            $createdOffers[] = $offer;
        }

        // ── Customers & Orders ──
        $customerNames = ['Budi Santoso', 'Sari Dewi', 'Ahmad Fauzi', 'Maya Putri', 'Rizky Pratama', 'Fitri Handayani', 'Dian Permatasari', 'Eko Wahyu'];

        foreach ($customerNames as $i => $name) {
            $customer = Customer::create([
                'workspace_id' => $workspace->id,
                'name' => $name,
                'email' => Str::slug($name) . '@example.com',
            ]);

            $offer = $createdOffers[$i % 3];
            $daysAgo = rand(1, 25);

            // Create paid order
            $order = Order::create([
                'workspace_id' => $workspace->id,
                'offer_id' => $offer->id,
                'customer_id' => $customer->id,
                'type' => 'offer_purchase',
                'status' => 'paid',
                'amount' => $offer->price,
                'currency' => 'IDR',
                'paid_at' => now()->subDays($daysAgo),
            ]);

            Payment::create([
                'order_id' => $order->id,
                'status' => 'paid',
                'amount' => $offer->price,
                'currency' => 'IDR',
                'payment_method' => 'bank_transfer',
                'paid_at' => now()->subDays($daysAgo),
            ]);

            CustomerAccess::create([
                'customer_id' => $customer->id,
                'offer_id' => $offer->id,
                'order_id' => $order->id,
                'status' => 'active',
                'granted_at' => now()->subDays($daysAgo),
            ]);

            // Subscriptions for membership offer
            if ($offer->type === OfferType::Subscription) {
                Subscription::create([
                    'workspace_id' => $workspace->id,
                    'customer_id' => $customer->id,
                    'offer_id' => $offer->id,
                    'status' => 'active',
                    'current_period_start' => now()->subDays($daysAgo),
                    'current_period_end' => now()->subDays($daysAgo)->addMonth(),
                ]);
            }

            // Credits for credit pack
            if ($offer->type === OfferType::CreditPack && $offer->credit_amount) {
                $wallet = CreditWallet::create([
                    'workspace_id' => $workspace->id,
                    'customer_id' => $customer->id,
                    'balance' => $offer->credit_amount,
                ]);
                $wallet->ledgers()->create([
                    'type' => 'credit',
                    'amount' => $offer->credit_amount,
                    'balance_after' => $offer->credit_amount,
                    'description' => 'Purchased: ' . $offer->title,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                ]);
            }
        }

        // ── Daily Metrics (last 30 days) ──
        for ($d = 30; $d >= 0; $d--) {
            DailyMetric::create([
                'workspace_id' => $workspace->id,
                'date' => now()->subDays($d)->toDateString(),
                'gross_revenue' => rand(50000, 600000),
                'paid_orders' => rand(0, 5),
                'new_customers' => rand(0, 3),
                'active_subscriptions' => rand(2, 8),
                'credits_sold' => rand(0, 200),
                'affiliate_revenue' => rand(0, 50000),
            ]);
        }

        // ── Affiliate Program ──
        AffiliateProgram::create([
            'workspace_id' => $workspace->id,
            'name' => 'Creator Referral Program',
            'commission_type' => 'percentage',
            'commission_value' => 20,
            'cookie_days' => 30,
            'is_active' => true,
        ]);
    }
}
