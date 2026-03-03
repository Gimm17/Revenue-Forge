<?php

namespace App\Http\Middleware;

use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ResolveWorkspace
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $workspace = null;

        // Try to resolve from current_workspace_id
        if ($user->current_workspace_id) {
            $workspace = Workspace::find($user->current_workspace_id);
        }

        // Fallback: pick first owned workspace
        if (! $workspace) {
            $workspace = $user->ownedWorkspaces()->first();

            if ($workspace) {
                $user->switchWorkspace($workspace);
            }
        }

        // If still no workspace, redirect to create one
        if (! $workspace) {
            if (! $request->routeIs('workspace.create', 'workspace.store')) {
                return redirect()->route('workspace.create');
            }
        }

        // Bind workspace to the request and container
        $request->attributes->set('workspace', $workspace);
        app()->instance('workspace', $workspace);

        // Share workspace data with Inertia
        Inertia::share('workspace', fn () => $workspace ? [
            'id' => $workspace->id,
            'name' => $workspace->name,
            'slug' => $workspace->slug,
            'logo_url' => $workspace->logo_url,
            'brand_color' => $workspace->brand_color,
            'is_active' => $workspace->is_active,
            'plan' => $workspace->plan ? [
                'name' => $workspace->plan->name,
                'slug' => $workspace->plan->slug,
            ] : null,
        ] : null);

        return $next($request);
    }
}
