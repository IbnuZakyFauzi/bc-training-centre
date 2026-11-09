<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eval1Exam extends Model
{
    use HasFactory;

    protected $table = 'eval1_exams';

    protected $fillable = [
        'title',
        'type',
        'material_id',
        'description',
        'duration_minutes',
        'passing_score',
        'proctoring_enabled',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'passing_score' => 'float',
        'proctoring_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Eval1Material::class, 'material_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Eval1Question::class, 'exam_id')->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Eval1ExamAttempt::class, 'exam_id');
    }

    public function latestAttemptFor(User $user): ?Eval1ExamAttempt
    {
        return $this->attempts()->where('user_id', $user->id)->latest()->first();
    }

    public function bestAttemptFor(User $user): ?Eval1ExamAttempt
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('total_score', 'desc')
            ->first();
    }

    public function isPassedBy(User $user): bool
    {
        $best = $this->bestAttemptFor($user);
        return $best && ((float) $best->total_score >= (float) $this->passing_score);
    }
}
