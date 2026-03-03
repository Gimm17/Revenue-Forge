<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Models\Offer;
use App\Models\OfferVersion;
use Illuminate\Http\RedirectResponse;

class OfferUpdateController extends Controller
{
    public function __invoke(StoreOfferRequest $request, Offer $offer): RedirectResponse
    {
        $data = $request->validated();
        $offer->update($data);

        // Create a new version snapshot
        $latestVersion = $offer->versions()->max('version') ?? 0;
        OfferVersion::create([
            'offer_id' => $offer->id,
            'version' => $latestVersion + 1,
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

        return back()->with('success', 'Offer updated successfully.');
    }
}
