<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CouponController extends Controller
{
    public function index(Request $request): Response
    {
        $workspace = $request->attributes->get('workspace');

        $coupons = Coupon::where('workspace_id', $workspace->id)
            ->with('offer:id,title')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'code' => $c->code,
                'type' => $c->type,
                'value' => $c->value,
                'offer' => $c->offer?->title ?? 'All Offers',
                'max_uses' => $c->max_uses,
                'used_count' => $c->used_count,
                'starts_at' => $c->starts_at?->format('M d, Y'),
                'expires_at' => $c->expires_at?->format('M d, Y'),
                'status' => $c->statusLabel(),
                'is_active' => $c->is_active,
                'discount_label' => $c->type === 'percentage'
                    ? $c->value . '%'
                    : 'Rp ' . number_format($c->value),
            ]);

        $offers = Offer::where('workspace_id', $workspace->id)
            ->where('is_published', true)
            ->select('id', 'title')
            ->get();

        return Inertia::render('Coupons/Index', [
            'coupons' => $coupons,
            'offers' => $offers,
        ]);
    }

    public function store(Request $request)
    {
        $workspace = $request->attributes->get('workspace');

        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'offer_id' => 'nullable|exists:offers,id',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['workspace_id'] = $workspace->id;
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return back()->with('success', 'Coupon created.');
    }

    public function toggle(Request $request, Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);
        return back()->with('success', 'Coupon ' . ($coupon->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    public function generate()
    {
        return response()->json([
            'code' => strtoupper(Str::random(8)),
        ]);
    }
}
