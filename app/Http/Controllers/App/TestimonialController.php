<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function index(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $testimonials = Testimonial::where('workspace_id', $workspace->id)
            ->with('offer:id,title')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'role' => $t->role,
                'content' => $t->content,
                'rating' => $t->rating,
                'offer' => $t->offer?->title ?? 'All Offers',
                'is_featured' => $t->is_featured,
            ]);

        $offers = Offer::where('workspace_id', $workspace->id)
            ->where('is_published', true)
            ->select('id', 'title')
            ->get();

        return Inertia::render('Testimonials/Index', [
            'testimonials' => $testimonials,
            'offers' => $offers,
        ]);
    }

    public function store(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'nullable|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'offer_id' => 'nullable|exists:offers,id',
            'is_featured' => 'boolean',
        ]);

        $validated['workspace_id'] = $workspace->id;
        $validated['sort_order'] = Testimonial::where('workspace_id', $workspace->id)->count();

        Testimonial::create($validated);

        return back()->with('success', 'Testimonial added.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted.');
    }

    public function toggleFeatured(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => !$testimonial->is_featured]);
        return back()->with('success', 'Testimonial updated.');
    }
}
