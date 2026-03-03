<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $fillable = ['offer_id', 'template', 'custom_css', 'meta_title', 'meta_description'];

    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
}
