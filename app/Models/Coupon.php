<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'workspace_id', 'offer_id', 'code', 'type', 'value',
        'max_uses', 'used_count', 'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(?int $offerId = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->offer_id && $offerId && $this->offer_id !== $offerId) return false;

        return true;
    }

    public function calculateDiscount(int $amount): int
    {
        if ($this->type === 'percentage') {
            return (int) round($amount * $this->value / 100);
        }
        return min($this->value, $amount); // Fixed: don't exceed amount
    }

    public function statusLabel(): string
    {
        if (!$this->is_active) return 'Inactive';
        if ($this->expires_at && $this->expires_at->isPast()) return 'Expired';
        if ($this->max_uses && $this->used_count >= $this->max_uses) return 'Exhausted';
        return 'Active';
    }
}
