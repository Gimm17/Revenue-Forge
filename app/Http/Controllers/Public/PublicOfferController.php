<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DailyMetric;
use App\Models\Offer;
use App\Models\OfferPageView;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicOfferController extends Controller
{
    public function __invoke(string $slug, Request $request): Response
    {
        $offer = Offer::where('slug', $slug)
            ->where('is_published', true)
            ->with(['workspace:id,name,brand_color', 'landingPage'])
            ->firstOrFail();

        // Track page view (silent, non-blocking)
        $this->trackPageView($offer, $request);

        // Get custom sections or generate defaults from offer data
        $sections = $offer->landingPage?->sections
            ?? \App\Models\LandingPage::defaultSectionsFromOffer($offer);

        return Inertia::render('Public/Offer', [
            'offer' => [
                'id' => $offer->id,
                'title' => $offer->title,
                'slug' => $offer->slug,
                'type' => $offer->type->value,
                'type_label' => $offer->type->label(),
                'tagline' => $offer->tagline,
                'short_pitch' => $offer->short_pitch,
                'long_copy' => $offer->long_copy,
                'benefits' => $offer->benefits ?? [],
                'faq' => $offer->faq ?? [],
                'cta_text' => $offer->cta_text ?? 'Buy Now',
                'price' => $offer->price,
                'formatted_price' => $offer->formattedPrice(),
                'currency' => $offer->currency,
                'interval' => $offer->interval,
                'credit_amount' => $offer->credit_amount,
            ],
            'workspace' => [
                'name' => $offer->workspace->name,
                'brand_color' => $offer->workspace->brand_color ?? '#06b6d4',
                'secondary_color' => $offer->workspace->secondary_color ?? '#8b5cf6',
                'font_family' => $offer->workspace->font_family ?? 'Inter',
                'chat_widget_enabled' => $offer->workspace->chat_widget_enabled ?? false,
                'chat_greeting' => $offer->workspace->chat_greeting ?? 'Hi! How can I help you?',
                'chat_email' => $offer->workspace->chat_email,
            ],
            'sections' => $sections,
        ]);
    }

    private function trackPageView(Offer $offer, Request $request): void
    {
        try {
            $visitorId = hash('sha256', $request->ip() . $request->userAgent());
            $ua = strtolower($request->userAgent() ?? '');
            $device = match (true) {
                str_contains($ua, 'mobile') || str_contains($ua, 'android') => 'mobile',
                str_contains($ua, 'tablet') || str_contains($ua, 'ipad') => 'tablet',
                default => 'desktop',
            };

            OfferPageView::create([
                'offer_id' => $offer->id,
                'workspace_id' => $offer->workspace_id,
                'visitor_id' => $visitorId,
                'referrer' => $request->header('referer'),
                'utm_source' => $request->query('utm_source'),
                'utm_medium' => $request->query('utm_medium'),
                'utm_campaign' => $request->query('utm_campaign'),
                'device_type' => $device,
                'viewed_at' => now(),
            ]);

            // Increment daily page views
            DailyMetric::firstOrCreate(
                ['workspace_id' => $offer->workspace_id, 'date' => now()->toDateString()],
                ['gross_revenue' => 0, 'paid_orders' => 0, 'new_customers' => 0, 'active_subscriptions' => 0, 'credits_sold' => 0, 'affiliate_revenue' => 0, 'page_views' => 0, 'checkouts_started' => 0]
            )->increment('page_views');
        } catch (\Exception $e) {
            // Silent fail — don't break the page for tracking errors
        }
    }
}
