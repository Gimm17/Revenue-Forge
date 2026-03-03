<?php

namespace App\Models;

use App\Data\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'workspace_id', 'offer_id', 'customer_id', 'type', 'status',
        'amount', 'currency', 'mayar_checkout_url', 'mayar_transaction_id',
        'metadata', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'metadata' => 'array',
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function latestPayment(): HasOne { return $this->hasOne(Payment::class)->latestOfMany(); }

    public function isPaid(): bool { return $this->status === OrderStatus::Paid; }

    public function markPaid(): void
    {
        $this->update(['status' => OrderStatus::Paid, 'paid_at' => now()]);
    }

    public function markFailed(): void
    {
        $this->update(['status' => OrderStatus::Failed]);
    }

    public function formattedAmount(): string
    {
        return $this->currency . ' ' . number_format($this->amount);
    }
}
