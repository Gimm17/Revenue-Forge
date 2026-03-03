<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $fillable = [
        'offer_id', 'template', 'theme', 'sections',
        'custom_css', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
        ];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    /**
     * Generate default sections from the offer data.
     */
    public static function defaultSectionsFromOffer(Offer $offer): array
    {
        $sections = [];
        $order = 0;

        // Hero section
        $sections[] = [
            'id' => uniqid('sec_'),
            'type' => 'hero',
            'order' => $order++,
            'content' => [
                'headline' => $offer->title,
                'subtitle' => $offer->tagline ?? '',
                'cta_text' => $offer->cta_text ?? 'Buy Now',
                'cta_color' => 'cyan',
            ],
        ];

        // Text section (short pitch)
        if ($offer->short_pitch) {
            $sections[] = [
                'id' => uniqid('sec_'),
                'type' => 'text',
                'order' => $order++,
                'content' => [
                    'body' => $offer->short_pitch,
                ],
            ];
        }

        // Benefits
        if (!empty($offer->benefits)) {
            $sections[] = [
                'id' => uniqid('sec_'),
                'type' => 'benefits',
                'order' => $order++,
                'content' => [
                    'items' => collect($offer->benefits)->map(fn($b) => [
                        'icon' => '✓',
                        'text' => $b,
                    ])->toArray(),
                ],
            ];
        }

        // Pricing
        $sections[] = [
            'id' => uniqid('sec_'),
            'type' => 'pricing',
            'order' => $order++,
            'content' => [
                'price' => $offer->price,
                'currency' => $offer->currency ?? 'IDR',
                'interval' => $offer->interval,
                'label' => $offer->title,
            ],
        ];

        // FAQ
        if (!empty($offer->faq)) {
            $sections[] = [
                'id' => uniqid('sec_'),
                'type' => 'faq',
                'order' => $order++,
                'content' => [
                    'items' => $offer->faq,
                ],
            ];
        }

        // Bottom CTA
        $sections[] = [
            'id' => uniqid('sec_'),
            'type' => 'cta',
            'order' => $order++,
            'content' => [
                'headline' => 'Ready to get started?',
                'button_text' => $offer->cta_text ?? 'Buy Now',
                'button_color' => 'violet',
            ],
        ];

        return $sections;
    }
}
