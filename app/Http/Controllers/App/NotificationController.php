<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\InAppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * View all Notifications (Inertia Page)
     */
    public function index(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $notifications = InAppNotification::where('workspace_id', $workspace->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Fetch unread/recent notifications for the Bell dropdown (JSON)
     */
    public function fetch(Request $request): JsonResponse
    {
        $workspace = $request->attributes->get('workspace');

        $notifications = InAppNotification::where('workspace_id', $workspace->id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        $unreadCount = InAppNotification::where('workspace_id', $workspace->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markRead(Request $request, InAppNotification $notification)
    {
        $notification->markRead();

        if ($request->header('X-Inertia')) {
            return back();
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        InAppNotification::where('workspace_id', $workspace->id)
            ->unread()
            ->update(['read_at' => now()]);

        if ($request->header('X-Inertia')) {
            return back();
        }

        return response()->json(['status' => 'ok']);
    }
}
