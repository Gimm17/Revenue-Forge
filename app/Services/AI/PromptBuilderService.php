<?php

namespace App\Services\AI;

class PromptBuilderService
{
    public function buildOfferPrompt(array $input): string
    {
        $businessType = $input['business_type'] ?? 'general business';
        $targetAudience = $input['target_audience'] ?? 'general audience';
        $offerType = $input['offer_type'] ?? 'one_time';
        $goal = $input['goal'] ?? 'increase sales';
        $priceRange = $input['price_range'] ?? 'mid-range';
        $tone = $input['tone'] ?? 'professional';
        $ctaStyle = $input['cta_style'] ?? 'direct';

        $offerTypeLabel = match ($offerType) {
            'one_time' => 'a one-time digital product',
            'subscription' => 'a subscription/membership',
            'credit_pack' => 'a credit pack',
            'saas_plan' => 'a SaaS workspace plan',
            default => 'a digital product',
        };

        return <<<PROMPT
You are a world-class copywriter and offer strategist for digital products and services.

Create a compelling offer for the following business:

**Business Type**: {$businessType}
**Target Audience**: {$targetAudience}
**Offer Type**: {$offerTypeLabel}
**Business Goal**: {$goal}
**Price Range**: {$priceRange}
**Tone**: {$tone}
**CTA Style**: {$ctaStyle}

Generate the following in JSON format:
{
  "title": "A compelling offer title (max 60 chars)",
  "tagline": "A punchy tagline (max 100 chars)",
  "short_pitch": "A 2-3 sentence elevator pitch",
  "long_copy": "A detailed sales copy (3-5 paragraphs in HTML format with <p>, <strong>, <em> tags)",
  "benefits": ["benefit 1", "benefit 2", "benefit 3", "benefit 4", "benefit 5"],
  "faq": [
    {"question": "FAQ question 1", "answer": "Answer 1"},
    {"question": "FAQ question 2", "answer": "Answer 2"},
    {"question": "FAQ question 3", "answer": "Answer 3"}
  ],
  "pricing_suggestion": "Suggested price with brief justification",
  "upsell_idea": "A complementary upsell offer idea",
  "cta": "Call-to-action button text (max 30 chars)"
}

Rules:
- Write in a {$tone} tone
- Make the CTA {$ctaStyle}
- Use power words and emotional triggers
- Make benefits specific and measurable where possible
- FAQs should address common objections
- The overall copy should feel premium and conversion-optimized
- Return ONLY valid JSON, no markdown formatting
PROMPT;
    }
}
