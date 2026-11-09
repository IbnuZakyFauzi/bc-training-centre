<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Eval1ExamAttempt extends Model
{
    use HasFactory;

    protected $table = 'eval1_exam_attempts';

    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'submitted_at',
        'answers',
        'total_score',
        'is_passed',
        'status',
        'suspicious_flags_count',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'total_score' => 'float',
        'is_passed' => 'boolean',
        'suspicious_flags_count' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Eval1Exam::class, 'exam_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function proctoringLogs(): HasMany
    {
        return $this->hasMany(Eval1ProctoringLog::class, 'attempt_id')->orderBy('created_at', 'desc');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Eval1Certificate::class, 'exam_attempt_id');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
