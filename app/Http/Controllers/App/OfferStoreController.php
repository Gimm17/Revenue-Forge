<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\OfferVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferStoreController extends Controller
{
    public function __invoke(StoreOfferRequest $request): RedirectResponse
    {
        $workspace = $request->attributes->get('workspace');
        $data = $request->validated();

        $offer = Offer::create([
            'workspace_id' => $workspace->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::random(5),
            'tagline' => $data['tagline'] ?? null,
            'short_pitch' => $data['short_pitch'] ?? null,
            'long_copy' => $data['long_copy'] ?? null,
            'benefits' => $data['benefits'] ?? [],
            'faq' => $data['faq'] ?? [],
            'cta_text' => $data['cta_text'] ?? 'Buy Now',
            'price' => $data['price'],
            'currency' => $data['currency'] ?? config('revenueforge.default_currency'),
            'interval' => $data['interval'] ?? null,
            'credit_amount' => $data['credit_amount'] ?? null,
        ]);

        // Create initial version
        OfferVersion::create([
            'offer_id' => $offer->id,
            'version' => 1,
            'content' => [
                'title' => $offer->title,
                'tagline' => $offer->tagline,
                'short_pitch' => $offer->short_pitch,
                'long_copy' => $offer->long_copy,
                'benefits' => $offer->benefits,
                'faq' => $offer->faq,
            ],
            'generated_by' => 'manual',
        ]);

        return redirect()->route('app.offers.edit', $offer)
            ->with('success', 'Offer created successfully.');
    }
}
