<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateLink extends Model
{
    protected $fillable = ['program_id', 'user_id', 'offer_id', 'code', 'url'];
    public function program(): BelongsTo { return $this->belongsTo(AffiliateProgram::class, 'program_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function clicks(): HasMany { return $this->hasMany(AffiliateClick::class, 'link_id'); }
    public function conversions(): HasMany { return $this->hasMany(AffiliateConversion::class, 'link_id'); }
}
