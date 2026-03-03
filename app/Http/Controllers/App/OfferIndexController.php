<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Data\Enums\OfferType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferIndexController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $filters = $request->only(['search', 'type']);

        $offers = $workspace->offers()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhere('slug', 'like', '%' . $search . '%');
                });
            })
            ->when($filters['type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            })
            ->withCount(['orders as paid_orders_count' => fn ($q) => $q->where('status', 'paid')])
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($offer) => [
                'id' => $offer->id,
                'title' => $offer->title,
                'slug' => $offer->slug,
                'type' => $offer->type,
                'type_label' => $offer->type->label(),
                'type_color' => $offer->type->color(),
                'price' => $offer->price,
                'currency' => $offer->currency,
                'formatted_price' => $offer->formattedPrice(),
                'is_published' => $offer->is_published,
                'paid_orders_count' => $offer->paid_orders_count,
                'created_at' => $offer->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Offers/Index', [
            'offers' => $offers,
            'offerTypes' => collect(OfferType::cases())->map(fn ($type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ]),
            'filters' => $filters,
        ]);
    }
}
