<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditWallet extends Model
{
    protected $fillable = ['workspace_id', 'customer_id', 'balance'];

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function ledgers(): HasMany { return $this->hasMany(CreditLedger::class, 'wallet_id'); }

    public function addCredits(int $amount, string $desc, ?string $refType = null, ?int $refId = null): void
    {
        $this->increment('balance', $amount);
        $this->ledgers()->create([
            'type' => 'credit', 'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $desc,
            'reference_type' => $refType, 'reference_id' => $refId,
        ]);
    }

    public function spendCredits(int $amount, string $desc, ?string $refType = null, ?int $refId = null): bool
    {
        if ($this->balance < $amount) return false;
        $this->decrement('balance', $amount);
        $this->ledgers()->create([
            'type' => 'debit', 'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $desc,
            'reference_type' => $refType, 'reference_id' => $refId,
        ]);
        return true;
    }
}
