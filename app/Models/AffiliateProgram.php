<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateProgram extends Model
{
    protected $fillable = ['workspace_id', 'name', 'commission_type', 'commission_value', 'cookie_days', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function links(): HasMany { return $this->hasMany(AffiliateLink::class, 'program_id'); }
}
