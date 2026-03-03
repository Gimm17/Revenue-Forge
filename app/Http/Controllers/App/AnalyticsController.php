<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\DailyMetric;
use App\Models\Offer;
use App\Models\OfferPageView;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');
        $days = (int) $request->input('days', 30);
        $since = now()->subDays($days);

        // === Daily Metrics (existing) ===
        $metrics = DailyMetric::where('workspace_id', $workspace->id)
            ->where('date', '>=', $since)
            ->orderBy('date')
            ->get()
            ->map(fn ($m) => [
                'date' => $m->date->format('M d'),
                'gross_revenue' => $m->gross_revenue,
                'paid_orders' => $m->paid_orders,
                'new_customers' => $m->new_customers,
                'active_subscriptions' => $m->active_subscriptions,
                'credits_sold' => $m->credits_sold,
                'affiliate_revenue' => $m->affiliate_revenue,
                'page_views' => $m->page_views ?? 0,
                'checkouts_started' => $m->checkouts_started ?? 0,
            ]);

        $totals = [
            'gross_revenue' => $metrics->sum('gross_revenue'),
            'paid_orders' => $metrics->sum('paid_orders'),
            'new_customers' => $metrics->sum('new_customers'),
            'credits_sold' => $metrics->sum('credits_sold'),
            'affiliate_revenue' => $metrics->sum('affiliate_revenue'),
            'page_views' => $metrics->sum('page_views'),
            'checkouts_started' => $metrics->sum('checkouts_started'),
        ];

        // === Funnel Data ===
        $totalViews = $totals['page_views'];
        $totalCheckouts = $totals['checkouts_started'];
        $totalPaid = $totals['paid_orders'];
        $funnel = [
            ['label' => 'Page Views', 'value' => $totalViews, 'color' => '#06b6d4'],
            ['label' => 'Checkouts', 'value' => $totalCheckouts, 'color' => '#8b5cf6'],
            ['label' => 'Paid Orders', 'value' => $totalPaid, 'color' => '#10b981'],
        ];

        // === Per-Offer Performance ===
        $offers = Offer::where('workspace_id', $workspace->id)
            ->where('is_published', true)
            ->select('id', 'title', 'slug', 'price', 'currency')
            ->get();

        $offerStats = $offers->map(function ($offer) use ($since) {
            $views = OfferPageView::where('offer_id', $offer->id)
                ->where('viewed_at', '>=', $since)
                ->count();

            $uniqueViews = OfferPageView::where('offer_id', $offer->id)
                ->where('viewed_at', '>=', $since)
                ->distinct('visitor_id')
                ->count('visitor_id');

            $orders = Order::where('offer_id', $offer->id)
                ->where('status', 'paid')
                ->where('paid_at', '>=', $since)
                ->count();

            $revenue = Order::where('offer_id', $offer->id)
                ->where('status', 'paid')
                ->where('paid_at', '>=', $since)
                ->sum('amount');

            $conversionRate = $uniqueViews > 0 ? round(($orders / $uniqueViews) * 100, 1) : 0;

            return [
                'id' => $offer->id,
                'title' => $offer->title,
                'slug' => $offer->slug,
                'views' => $views,
                'unique_views' => $uniqueViews,
                'orders' => $orders,
                'revenue' => $revenue,
                'conversion_rate' => $conversionRate,
                'formatted_revenue' => $offer->currency . ' ' . number_format($revenue),
            ];
        })->sortByDesc('revenue')->values();

        // === Traffic Sources ===
        $trafficSources = OfferPageView::where('workspace_id', $workspace->id)
            ->where('viewed_at', '>=', $since)
            ->select(
                DB::raw("COALESCE(utm_source, CASE WHEN referrer IS NULL OR referrer = '' THEN 'direct' ELSE 'organic' END) as source"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('source')
            ->orderByDesc('count')
            ->limit(6)
            ->get();

        // === Device Breakdown ===
        $deviceBreakdown = OfferPageView::where('workspace_id', $workspace->id)
            ->where('viewed_at', '>=', $since)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->device_type ?? 'unknown' => $item->count]);

        return Inertia::render('Analytics/Index', [
            'metrics' => $metrics,
            'totals' => $totals,
            'days' => $days,
            'funnel' => $funnel,
            'offerStats' => $offerStats,
            'trafficSources' => $trafficSources,
            'deviceBreakdown' => $deviceBreakdown,
        ]);
    }
}
