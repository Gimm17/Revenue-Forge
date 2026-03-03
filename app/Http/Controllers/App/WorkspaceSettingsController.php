<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceSettingsController extends Controller
{
    public function edit(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        return Inertia::render('Settings/WorkspaceSettings', [
            'workspace' => [
                'id' => $workspace->id,
                'name' => $workspace->name,
                'slug' => $workspace->slug,
                'logo_url' => $workspace->logo_url,
                'brand_color' => $workspace->brand_color,
                'is_active' => $workspace->is_active,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:workspaces,slug,' . $workspace->id,
            'brand_color' => 'nullable|string|max:7',
        ]);

        $workspace->update($validated);

        return back()->with('success', 'Workspace settings updated.');
    }
}
