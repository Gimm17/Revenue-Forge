<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerShowController extends Controller
{
    public function __invoke(Request $request, Customer $customer): Response
    {
        $customer->load([
            'orders' => fn ($q) => $q->with('offer:id,title,type')->latest()->take(10),
            'access' => fn ($q) => $q->with('offer:id,title')->latest(),
            'subscriptions' => fn ($q) => $q->with('offer:id,title')->latest(),
            'creditWallet',
        ]);

        return Inertia::render('Customers/Show', [
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'created_at' => $customer->created_at->format('M d, Y'),
            ],
            'orders' => $customer->orders->map(fn ($o) => [
                'id' => $o->id,
                'offer_title' => $o->offer?->title ?? 'N/A',
                'status' => $o->status->value,
                'amount' => $o->amount,
                'currency' => $o->currency,
                'paid_at' => $o->paid_at?->diffForHumans(),
            ]),
            'access' => $customer->access->map(fn ($a) => [
                'id' => $a->id,
                'offer_title' => $a->offer?->title ?? 'N/A',
                'status' => $a->status->value,
                'granted_at' => $a->granted_at->format('M d, Y'),
                'expires_at' => $a->expires_at?->format('M d, Y'),
            ]),
            'subscriptions' => $customer->subscriptions->map(fn ($s) => [
                'id' => $s->id,
                'offer_title' => $s->offer?->title ?? 'N/A',
                'status' => $s->status->value,
                'current_period_end' => $s->current_period_end?->format('M d, Y'),
            ]),
            'creditBalance' => $customer->creditWallet?->balance ?? 0,
        ]);
    }
}
