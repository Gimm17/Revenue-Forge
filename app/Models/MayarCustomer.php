<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MayarCustomer extends Model
{
    protected $fillable = ['customer_id', 'mayar_id', 'email', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array']; }
    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
}
