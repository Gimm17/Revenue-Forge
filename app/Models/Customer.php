<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = ['workspace_id', 'name', 'email', 'phone', 'mayar_customer_id', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array']; }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function access(): HasMany { return $this->hasMany(CustomerAccess::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function subscriptions(): HasMany { return $this->hasMany(Subscription::class); }
    public function creditWallet(): HasOne { return $this->hasOne(CreditWallet::class); }
    public function portalSessions(): HasMany { return $this->hasMany(PortalSessionLog::class); }
}
