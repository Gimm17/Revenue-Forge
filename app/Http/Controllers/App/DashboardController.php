<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $metrics = [
            'gross_revenue' => 0,
            'paid_orders' => 0,
            'active_subscriptions' => 0,
            'credits_sold' => 0,
            'new_customers' => 0,
        ];

        $recentPayments = [];
        $topOffers = [];

        if ($workspace) {
            $metrics['gross_revenue'] = $workspace->orders()
                ->where('status', 'paid')
                ->sum('amount');

            $metrics['paid_orders'] = $workspace->orders()
                ->where('status', 'paid')
                ->count();

            $metrics['new_customers'] = $workspace->customers()
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            $recentPayments = $workspace->orders()
                ->with('offer:id,title,type')
                ->where('status', 'paid')
                ->latest('paid_at')
                ->take(5)
                ->get()
                ->map(fn ($order) => [
                    'id' => $order->id,
                    'offer_title' => $order->offer?->title ?? 'N/A',
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                    'paid_at' => $order->paid_at?->diffForHumans(),
                ]);

            $topOffers = $workspace->offers()
                ->withCount(['orders as paid_orders_count' => function ($q) {
                    $q->where('status', 'paid');
                }])
                ->withSum(['orders as total_revenue' => function ($q) {
                    $q->where('status', 'paid');
                }], 'amount')
                ->orderByDesc('total_revenue')
                ->take(5)
                ->get()
                ->map(fn ($offer) => [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'type' => $offer->type,
                    'paid_orders_count' => $offer->paid_orders_count,
                    'total_revenue' => $offer->total_revenue ?? 0,
                ]);
        }

        return Inertia::render('Dashboard', [
            'metrics' => $metrics,
            'recentPayments' => $recentPayments,
            'topOffers' => $topOffers,
        ]);
    }
}
