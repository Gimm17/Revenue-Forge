<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\AffiliateProgram;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AffiliateController extends Controller
{
    public function index(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $programs = AffiliateProgram::where('workspace_id', $workspace->id)
            ->withCount('links')
            ->latest()
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'commission_type' => $p->commission_type,
                'commission_value' => $p->commission_value,
                'is_active' => $p->is_active,
                'links_count' => $p->links_count,
            ]);

        return Inertia::render('Affiliates/Index', ['programs' => $programs]);
    }

    public function store(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|integer|min:1',
            'cookie_days' => 'nullable|integer|min:1|max:365',
        ]);

        AffiliateProgram::create([
            'workspace_id' => $workspace->id,
            ...$validated,
        ]);

        return back()->with('success', 'Affiliate program created.');
    }
}
