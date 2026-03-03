<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingPageBuilderController extends Controller
{
    public function show(Offer $offer)
    {
        $this->authorizeOffer($offer);

        $landingPage = $offer->landingPage;

        // Generate default sections from offer data if none exist
        $sections = $landingPage?->sections
            ?? LandingPage::defaultSectionsFromOffer($offer);

        return Inertia::render('Offers/LandingPageBuilder', [
            'offer' => $offer->only('id', 'title', 'slug', 'is_published', 'price', 'currency', 'interval', 'cta_text', 'type'),
            'landingPage' => [
                'sections' => $sections,
                'theme' => $landingPage?->theme ?? 'dark',
                'custom_css' => $landingPage?->custom_css ?? '',
                'meta_title' => $landingPage?->meta_title ?? $offer->title,
                'meta_description' => $landingPage?->meta_description ?? $offer->short_pitch,
            ],
        ]);
    }

    public function update(Request $request, Offer $offer)
    {
        $this->authorizeOffer($offer);

        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|string',
            'sections.*.type' => 'required|string|in:hero,text,benefits,pricing,faq,testimonial,cta,image,video,divider',
            'sections.*.order' => 'required|integer',
            'sections.*.content' => 'required|array',
            'theme' => 'nullable|string|in:dark,light,gradient',
            'custom_css' => 'nullable|string|max:10000',
            'meta_title' => 'nullable|string|max:120',
            'meta_description' => 'nullable|string|max:300',
        ]);

        $offer->landingPage()->updateOrCreate(
            ['offer_id' => $offer->id],
            [
                'sections' => $validated['sections'],
                'theme' => $validated['theme'] ?? 'dark',
                'custom_css' => $validated['custom_css'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Landing page saved!');
    }

    private function authorizeOffer(Offer $offer): void
    {
        $workspace = app('workspace');
        abort_unless($workspace && $offer->workspace_id === $workspace->id, 403);
    }
}
