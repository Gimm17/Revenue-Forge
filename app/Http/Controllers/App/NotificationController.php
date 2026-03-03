<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\InAppNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for current workspace
     */
    public function index(Request $request): JsonResponse
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
    public function markRead(Request $request, InAppNotification $notification): JsonResponse
    {
        $notification->markRead();

        return response()->json(['status' => 'ok']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $workspace = $request->attributes->get('workspace');

        InAppNotification::where('workspace_id', $workspace->id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }
}
