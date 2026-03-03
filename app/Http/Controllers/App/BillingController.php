<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $orders = $workspace->orders()
            ->with(['offer:id,title,type', 'customer:id,name,email', 'latestPayment'])
            ->latest()
            ->paginate(20)
            ->through(fn ($order) => [
                'id' => $order->id,
                'offer_title' => $order->offer?->title ?? 'N/A',
                'customer_name' => $order->customer?->name ?? 'N/A',
                'customer_email' => $order->customer?->email ?? '',
                'status' => $order->status->value,
                'status_label' => ucfirst($order->status->value),
                'amount' => $order->amount,
                'formatted_amount' => $order->formattedAmount(),
                'payment_method' => $order->latestPayment?->payment_method ?? '—',
                'paid_at' => $order->paid_at?->diffForHumans(),
                'created_at' => $order->created_at->format('M d, Y'),
            ]);

        $stats = [
            'total_revenue' => $workspace->orders()->where('status', 'paid')->sum('amount'),
            'total_orders' => $workspace->orders()->count(),
            'paid_orders' => $workspace->orders()->where('status', 'paid')->count(),
            'pending_orders' => $workspace->orders()->where('status', 'pending')->count(),
        ];

        return Inertia::render('Billing/Index', [
            'orders' => $orders,
            'stats' => $stats,
        ]);
    }
}
