<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Services\Mayar\MayarCheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OfferCheckoutController extends Controller
{
    public function __invoke(Request $request, string $slug, MayarCheckoutService $checkout): RedirectResponse
    {
        $offer = Offer::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'affiliate_code' => 'nullable|string',
        ]);

        if (empty($validated['affiliate_code']) && $request->hasCookie('revenueforge_affiliate_ref')) {
            $validated['affiliate_code'] = $request->cookie('revenueforge_affiliate_ref');
        }

        $result = $checkout->createCheckout($offer, $validated);

        if ($result['success']) {
            return redirect($result['checkout_url']);
        }

        return back()->with('error', $result['error'] ?? 'Checkout failed. Please try again.');
    }
}
