<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\EmailBroadcast;
use App\Mail\BroadcastEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class BroadcastController extends Controller
{
    public function index(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $broadcasts = EmailBroadcast::where('workspace_id', $workspace->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'subject' => $b->subject,
                'status' => $b->statusLabel(),
                'recipients_count' => $b->recipients_count,
                'sent_count' => $b->sent_count,
                'sent_at' => $b->sent_at?->format('M d, Y H:i'),
                'created_at' => $b->created_at->format('M d, Y'),
            ]);

        $customerCount = Customer::where('workspace_id', $workspace->id)->count();

        return Inertia::render('Broadcasts/Index', [
            'broadcasts' => $broadcasts,
            'customerCount' => $customerCount,
        ]);
    }

    public function store(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:10000',
        ]);

        $customers = Customer::where('workspace_id', $workspace->id)
            ->whereNotNull('email')
            ->get();

        $broadcast = EmailBroadcast::create([
            'workspace_id' => $workspace->id,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'status' => 'sending',
            'recipients_count' => $customers->count(),
        ]);

        $sentCount = 0;
        foreach ($customers as $customer) {
            try {
                Mail::to($customer->email)->queue(new BroadcastEmail(
                    $validated['subject'],
                    $validated['body'],
                    $workspace->name,
                ));
                $sentCount++;
            } catch (\Exception $e) {
                // Skip failed emails silently
            }
        }

        $broadcast->update([
            'status' => 'sent',
            'sent_count' => $sentCount,
            'sent_at' => now(),
        ]);

        return back()->with('success', "Broadcast sent to {$sentCount} customers.");
    }

    public function destroy(EmailBroadcast $broadcast)
    {
        $broadcast->delete();
        return back()->with('success', 'Broadcast deleted.');
    }
}
