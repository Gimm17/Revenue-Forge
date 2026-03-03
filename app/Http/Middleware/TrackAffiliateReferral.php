<?php

namespace App\Http\Middleware;

use App\Models\AffiliateClick;
use App\Models\AffiliateLink;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class TrackAffiliateReferral
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $code = $request->query('ref');
            $link = AffiliateLink::where('code', $code)->with('program')->first();

            if ($link && $link->program && $link->program->is_active) {
                // Determine cookie duration
                $minutes = $link->program->cookie_days * 24 * 60;
                
                // Track click if not tracked recently in this session to prevent spam
                if (! $request->session()->has("tracked_affiliate_click_{$code}")) {
                    AffiliateClick::create([
                        'link_id' => $link->id,
                        'ip_address' => $request->ip() ?? '0.0.0.0',
                        'user_agent' => $request->userAgent() ?? 'Unknown',
                        'referrer' => $request->headers->get('referer'),
                        'clicked_at' => now(),
                    ]);
                    $request->session()->put("tracked_affiliate_click_{$code}", true);
                }

                $response = $next($request);
                
                // Return response with cookie attached
                return $response->withCookie(cookie('revenueforge_affiliate_ref', $code, $minutes));
            }
        }

        return $next($request);
    }
}
