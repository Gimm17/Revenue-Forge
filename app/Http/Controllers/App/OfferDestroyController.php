<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferDestroyController extends Controller
{
    public function __invoke(Request $request, Offer $offer)
    {
        $workspace = $request->attributes->get('workspace');
        
        if ($offer->workspace_id !== $workspace->id) {
            abort(403);
        }

        $offer->delete();

        return redirect()->route('app.offers.index')->with('success', 'Offer deleted successfully.');
    }
}
