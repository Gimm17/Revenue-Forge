<?php

namespace App\Models;

use App\Data\Enums\OfferType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'workspace_id', 'type', 'title', 'slug', 'tagline',
        'short_pitch', 'long_copy', 'benefits', 'faq',
        'pricing_suggestion', 'upsell_idea', 'cta_text',
        'price', 'currency', 'interval', 'credit_amount',
        'is_published', 'published_at', 'mayar_product_id',
        'cover_image_url',
        'delivery_type', 'delivery_content', 'delivery_label',
    ];

    protected function casts(): array
    {
        return [
            'type' => OfferType::class,
            'benefits' => 'array',
            'faq' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'price' => 'integer',
            'credit_amount' => 'integer',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(OfferVersion::class);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(OfferVersion::class)->latestOfMany();
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(LandingPage::class);
    }

    public function aiGenerations(): HasMany
    {
        return $this->hasMany(AiGeneration::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOfType($query, OfferType $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function formattedPrice(): string
    {
        return $this->currency . ' ' . number_format($this->price);
    }

    public function isSubscription(): bool
    {
        return $this->type === OfferType::Subscription;
    }

    public function isCreditPack(): bool
    {
        return $this->type === OfferType::CreditPack;
    }
}
