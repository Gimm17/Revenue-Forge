<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerIndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $filters = $request->only(['search']);

        $customers = $workspace->customers()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->withCount(['orders as paid_orders_count' => fn ($q) => $q->where('status', 'paid')])
            ->withSum(['orders as total_spent' => fn ($q) => $q->where('status', 'paid')], 'amount')
            ->latest()
            ->paginate(20)
            ->withQueryString()
            ->through(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'email' => $c->email,
                'phone' => $c->phone,
                'paid_orders_count' => $c->paid_orders_count,
                'total_spent' => $c->total_spent ?? 0,
                'created_at' => $c->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $filters,
        ]);
    }
}
