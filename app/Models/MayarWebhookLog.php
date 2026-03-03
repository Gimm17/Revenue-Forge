<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MayarWebhookLog extends Model
{
    protected $fillable = ['event_type', 'event_id', 'payload', 'signature', 'status', 'error_message', 'processed_at'];
    protected function casts(): array { return ['payload' => 'array', 'processed_at' => 'datetime']; }
    public function markProcessed(): void { $this->update(['status' => 'processed', 'processed_at' => now()]); }
    public function markFailed(string $error): void { $this->update(['status' => 'failed', 'error_message' => $error]); }
}
