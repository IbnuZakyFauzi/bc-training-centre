<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eval1Certificate extends Model
{
    use HasFactory;

    protected $table = 'eval1_certificates';

    protected $fillable = [
        'user_id',
        'exam_attempt_id',
        'certificate_number',
        'license_type',
        'issue_date',
        'valid_until',
        'score',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'score' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Eval1ExamAttempt::class, 'exam_attempt_id');
    }

    public static function generateCertificateNumber(User $user): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        $sid = $user->sid ?? 'TRN';
        return "SIMPER-L1/{$year}/{$sid}/{$random}";
    }
}
