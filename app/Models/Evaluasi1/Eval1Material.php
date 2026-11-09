<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Eval1Material extends Model
{
    use HasFactory;

    protected $table = 'eval1_materials';

    protected $fillable = [
        'title',
        'description',
        'type',
        'content_url',
        'file_path',
        'order',
        'min_duration_seconds',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'min_duration_seconds' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Eval1MaterialProgress::class, 'material_id');
    }

    public function postTest(): HasOne
    {
        return $this->hasOne(Eval1Exam::class, 'material_id')->where('type', 'post_test');
    }

    public function isCompletedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->progresses()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }

    public function postTestAttemptFor(?User $user): ?Eval1ExamAttempt
    {
        if (! $user) {
            return null;
        }

        $postTest = $this->postTest;
        if (! $postTest) {
            return null;
        }

        return $postTest->bestAttemptFor($user);
    }

    public function isPostTestPassedBy(?User $user): bool
    {
        $attempt = $this->postTestAttemptFor($user);
        return $attempt && ((float) $attempt->total_score >= 80.0);
    }

    public function isPostTestRemedialFor(?User $user): bool
    {
        $attempt = $this->postTestAttemptFor($user);
        return $attempt && ((float) $attempt->total_score < 80.0);
    }
}
