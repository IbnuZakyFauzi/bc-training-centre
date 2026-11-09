<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eval1ProctoringLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eval1_proctoring_logs';

    protected $fillable = [
        'attempt_id',
        'user_id',
        'snapshot_path',
        'event_type',
        'event_details',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Eval1ExamAttempt::class, 'attempt_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
