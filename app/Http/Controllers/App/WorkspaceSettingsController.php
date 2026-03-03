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
                'secondary_color' => $workspace->secondary_color,
                'font_family' => $workspace->font_family,
                'chat_widget_enabled' => $workspace->chat_widget_enabled,
                'chat_greeting' => $workspace->chat_greeting,
                'chat_email' => $workspace->chat_email,
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
            'secondary_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:50',
            'chat_widget_enabled' => 'boolean',
            'chat_greeting' => 'nullable|string|max:255',
            'chat_email' => 'nullable|email|max:255',
        ]);

        $workspace->update($validated);

        return back()->with('success', 'Workspace settings updated.');
    }
}
