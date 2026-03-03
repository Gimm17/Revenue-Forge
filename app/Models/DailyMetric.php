<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMetric extends Model
{
    protected $fillable = ['workspace_id', 'date', 'gross_revenue', 'paid_orders', 'new_customers', 'active_subscriptions', 'credits_sold', 'affiliate_revenue', 'page_views', 'checkouts_started'];
    protected function casts(): array { return ['date' => 'date']; }
    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
}
