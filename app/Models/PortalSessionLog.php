<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalSessionLog extends Model
{
    protected $table = 'portal_sessions_log';
    protected $fillable = ['customer_id', 'type', 'status', 'sent_at', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array', 'sent_at' => 'datetime']; }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
