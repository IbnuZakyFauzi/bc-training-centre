<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineePhaseHistory extends Model
{
    protected $table = 'trainee_phase_histories';

    protected $fillable = [
        'user_id',
        'from_phase',
        'to_phase',
        'evaluation_id',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(FinalEvaluation::class, 'evaluation_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
