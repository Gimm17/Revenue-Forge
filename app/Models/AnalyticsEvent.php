<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    public $timestamps = false;
    protected $fillable = ['workspace_id', 'event_type', 'event_data', 'created_at'];
    protected function casts(): array { return ['event_data' => 'array', 'created_at' => 'datetime']; }
    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
}
