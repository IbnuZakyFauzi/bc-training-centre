<?php

namespace App\Models\Evaluasi2;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eval2Certificate extends Model
{
    use HasFactory;

    protected $table = 'eval2_certificates';

    protected $fillable = [
        'trainee_id',
        'assessment_id',
        'certificate_number',
        'license_type',
        'issue_date',
        'valid_until',
        'total_score',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'total_score' => 'float',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Eval2GerdasAssessment::class, 'assessment_id');
    }

    public static function generateCertificateNumber(User $trainee): string
    {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -4));
        $sid = $trainee->sid ?? 'TRN';
        return "SIMPER-L2/{$year}/{$sid}/{$random}";
    }
}
