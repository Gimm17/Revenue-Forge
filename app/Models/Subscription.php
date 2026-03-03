<?php
namespace App\Models;

use App\Data\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'workspace_id', 'customer_id', 'offer_id', 'status',
        'mayar_subscription_id', 'current_period_start', 'current_period_end', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }

    public function isActive(): bool { return $this->status->isActive(); }

    public function cancel(): void
    {
        $this->update(['status' => SubscriptionStatus::Cancelled, 'cancelled_at' => now()]);
    }
}
