<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OfferPublishController extends Controller
{
    public function __invoke(Request $request, Offer $offer): RedirectResponse
    {
        $offer->update([
            'is_published' => !$offer->is_published,
            'published_at' => !$offer->is_published ? now() : null,
        ]);

        $status = $offer->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Offer {$status} successfully.");
    }
}
