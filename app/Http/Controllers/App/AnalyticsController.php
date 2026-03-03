<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\DailyMetric;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');
        $days = (int) $request->input('days', 30);

        $metrics = DailyMetric::where('workspace_id', $workspace->id)
            ->where('date', '>=', now()->subDays($days))
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
            ]);

        $totals = [
            'gross_revenue' => $metrics->sum('gross_revenue'),
            'paid_orders' => $metrics->sum('paid_orders'),
            'new_customers' => $metrics->sum('new_customers'),
            'credits_sold' => $metrics->sum('credits_sold'),
            'affiliate_revenue' => $metrics->sum('affiliate_revenue'),
        ];

        return Inertia::render('Analytics/Index', [
            'metrics' => $metrics,
            'totals' => $totals,
            'days' => $days,
        ]);
    }
}
