<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Inertia\Inertia;
use Inertia\Response;

class PublicOfferController extends Controller
{
    public function __invoke(string $slug): Response
    {
        $offer = Offer::where('slug', $slug)
            ->where('is_published', true)
            ->with('workspace:id,name,brand_color')
            ->firstOrFail();

        return Inertia::render('Public/Offer', [
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
                'cta_text' => $offer->cta_text ?? 'Buy Now',
                'price' => $offer->price,
                'formatted_price' => $offer->formattedPrice(),
                'currency' => $offer->currency,
                'interval' => $offer->interval,
                'credit_amount' => $offer->credit_amount,
            ],
            'workspace' => [
                'name' => $offer->workspace->name,
                'brand_color' => $offer->workspace->brand_color ?? '#06b6d4',
            ],
        ]);
    }
}
