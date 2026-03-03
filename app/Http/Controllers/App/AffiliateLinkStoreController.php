<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLink;
use App\Models\AffiliateProgram;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateLinkStoreController extends Controller
{
    public function __invoke(Request $request, AffiliateProgram $program): RedirectResponse
    {
        $workspace = $request->attributes->get('workspace');
        
        if ($program->workspace_id !== $workspace->id) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'offer_id' => 'nullable|exists:offers,id',
        ]);

        // Find or create the user (Affiliate)
        $user = User::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => bcrypt(Str::random(16)), // They can reset password later if needed
            ]
        );

        $code = Str::random(8); // e.g. ref_1234abcd
        $url = route('landing') . '?ref=' . $code; // fallback URL
        
        if ($validated['offer_id']) {
            $offer = Offer::find($validated['offer_id']);
            if ($offer && $offer->workspace_id === $workspace->id) {
                $url = route('offer.show', $offer->slug) . '?ref=' . $code;
            }
        }

        AffiliateLink::create([
            'program_id' => $program->id,
            'user_id' => $user->id,
            'offer_id' => $validated['offer_id'] ?? null,
            'code' => $code,
            'url' => $url,
        ]);

        return back()->with('success', 'Affiliate link generated successfully.');
    }
}
