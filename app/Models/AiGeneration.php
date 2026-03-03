<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGeneration extends Model
{
    protected $fillable = [
        'workspace_id', 'offer_id', 'user_id',
        'input_params', 'output_content',
        'model_used', 'tokens_used', 'duration_ms',
    ];

    protected function casts(): array
    {
        return [
            'input_params' => 'array',
            'output_content' => 'array',
        ];
    }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function offer(): BelongsTo { return $this->belongsTo(Offer::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
