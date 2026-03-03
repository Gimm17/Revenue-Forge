<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferDuplicateController extends Controller
{
    public function __invoke(Request $request, Offer $offer)
    {
        $workspace = $request->attributes->get('workspace');

        // Ensure the offer belongs to this workspace
        if ($offer->workspace_id !== $workspace->id) {
            abort(403);
        }

        $newOffer = $offer->replicate([
            'is_published', 'published_at', 'mayar_product_id', 'slug',
        ]);

        $newOffer->title = $offer->title . ' (Copy)';
        $newOffer->slug = Str::slug($newOffer->title) . '-' . Str::random(4);
        $newOffer->is_published = false;
        $newOffer->published_at = null;
        $newOffer->mayar_product_id = null;
        $newOffer->save();

        return redirect()->route('app.offers.edit', $newOffer->id)
            ->with('success', 'Offer duplicated! Edit it below.');
    }
}
