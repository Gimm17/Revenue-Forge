<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferVersion extends Model
{
    protected $fillable = ['offer_id', 'version', 'content', 'generated_by'];

    protected function casts(): array
    {
        return ['content' => 'array'];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }
}
