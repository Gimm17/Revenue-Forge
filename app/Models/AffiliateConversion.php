<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateConversion extends Model
{
    protected $fillable = ['link_id', 'click_id', 'order_id', 'commission_amount', 'status'];
    public function link(): BelongsTo { return $this->belongsTo(AffiliateLink::class, 'link_id'); }
    public function click(): BelongsTo { return $this->belongsTo(AffiliateClick::class, 'click_id'); }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
