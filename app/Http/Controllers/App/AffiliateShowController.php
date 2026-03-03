<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProgram;
use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AffiliateShowController extends Controller
{
    public function __invoke(Request $request, AffiliateProgram $program): Response
    {
        $workspace = $request->attributes->get('workspace');
        
        if ($program->workspace_id !== $workspace->id) {
            abort(403);
        }

        $program->load(['links.user', 'links.offer']);
        $program->loadCount('links');

        $offers = $workspace->offers()->where('is_published', true)->get(['id', 'title', 'slug']);

        return Inertia::render('Affiliates/Show', [
            'program' => [
                'id' => $program->id,
                'name' => $program->name,
                'commission_type' => $program->commission_type,
                'commission_value' => $program->commission_value,
                'cookie_days' => $program->cookie_days,
                'is_active' => $program->is_active,
                'links_count' => $program->links_count,
                'links' => $program->links->map(fn ($link) => [
                    'id' => $link->id,
                    'code' => $link->code,
                    'url' => $link->url,
                    'offer_title' => $link->offer ? $link->offer->title : 'All Offers',
                    'affiliate_name' => $link->user->name,
                    'affiliate_email' => $link->user->email,
                    'clicks_count' => $link->clicks()->count(), // simplistic for MVP
                    'conversions_count' => $link->conversions()->count(), // simplistic for MVP
                    'revenue' => $link->conversions()->sum('commission_amount'),
                ]),
            ],
            'offers' => $offers,
        ]);
    }
}
