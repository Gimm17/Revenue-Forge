<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Data\Enums\OfferType;
use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferEditController extends Controller
{
    public function __invoke(Request $request, Offer $offer): Response
    {
        $offer->load(['versions' => fn ($q) => $q->latest()->take(5)]);

        return Inertia::render('Offers/Edit', [
            'offer' => [
                'id' => $offer->id,
                'title' => $offer->title,
                'slug' => $offer->slug,
                'type' => $offer->type->value,
                'type_label' => $offer->type->label(),
                'tagline' => $offer->tagline,
                'short_pitch' => $offer->short_pitch,
                'long_copy' => $offer->long_copy,
                'benefits' => $offer->benefits ?? [],
                'faq' => $offer->faq ?? [],
                'cta_text' => $offer->cta_text,
                'price' => $offer->price,
                'currency' => $offer->currency,
                'interval' => $offer->interval,
                'credit_amount' => $offer->credit_amount,
                'is_published' => $offer->is_published,
                'published_at' => $offer->published_at?->format('M d, Y H:i'),
            ],
            'versions' => $offer->versions->map(fn ($v) => [
                'id' => $v->id,
                'version' => $v->version,
                'generated_by' => $v->generated_by,
                'created_at' => $v->created_at->diffForHumans(),
            ]),
            'offerTypes' => collect(OfferType::cases())->map(fn ($type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'color' => $type->color(),
            ]),
        ]);
    }
}
