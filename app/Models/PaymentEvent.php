<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentEvent extends Model
{
    protected $fillable = ['payment_id', 'event_type', 'event_key', 'payload', 'processed', 'processed_at'];
    protected function casts(): array { return ['payload' => 'array', 'processed' => 'boolean', 'processed_at' => 'datetime']; }
    public function payment(): BelongsTo { return $this->belongsTo(Payment::class); }
    public function scopeUnprocessed($q) { return $q->where('processed', false); }
}
