<?php
namespace App\Models;

use App\Data\Enums\AccessStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAccess extends Model
{
    protected $table = 'customer_access';
    protected $fillable = ['customer_id', 'offer_id', 'order_id', 'status', 'granted_at', 'expires_at', 'revoked_at'];
    protected function casts(): array { return ['status' => AccessStatus::class, 'granted_at' => 'datetime', 'expires_at' => 'datetime', 'revoked_at' => 'datetime']; }

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function scopeActive($q) { return $q->where('status', AccessStatus::Active); }
}
