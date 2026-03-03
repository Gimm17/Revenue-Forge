<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\MayarWebhookLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WebhookLogController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $logs = MayarWebhookLog::orderByDesc('created_at')
            ->take(100)
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'event_type' => $log->event_type,
                'event_id' => $log->event_id,
                'status' => $log->status,
                'error_message' => $log->error_message,
                'payload_preview' => json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
                'processed_at' => $log->processed_at?->format('M d, Y H:i:s'),
                'created_at' => $log->created_at->format('M d, Y H:i:s'),
            ]);

        return Inertia::render('Webhooks/Index', [
            'logs' => $logs,
        ]);
    }
}
