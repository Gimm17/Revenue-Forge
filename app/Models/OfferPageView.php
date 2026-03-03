<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferPageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'offer_id', 'workspace_id', 'visitor_id',
        'referrer', 'utm_source', 'utm_medium', 'utm_campaign',
        'device_type', 'viewed_at',
    ];

    protected function casts(): array
    {
        return ['viewed_at' => 'datetime'];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
