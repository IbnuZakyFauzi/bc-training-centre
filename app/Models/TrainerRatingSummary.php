<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainerRatingSummary extends Model
{
    protected $fillable = ['user_id', 'average_rating', 'rating_count'];

    protected function casts(): array
    {
        return [
            'average_rating' => 'decimal:1',
            'rating_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
