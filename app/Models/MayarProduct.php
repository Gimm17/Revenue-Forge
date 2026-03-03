<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MayarProduct extends Model
{
    protected $fillable = ['workspace_id', 'offer_id', 'mayar_id', 'name', 'type', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array']; }
    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
}
