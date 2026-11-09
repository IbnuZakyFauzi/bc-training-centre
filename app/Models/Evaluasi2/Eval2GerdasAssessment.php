<?php

namespace App\Models\Evaluasi2;

use App\Models\EquipmentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Eval2GerdasAssessment extends Model
{
    use HasFactory;

    protected $table = 'eval2_gerdas_assessments';

    protected $fillable = [
        'trainee_id',
        'trainer_id',
        'equipment_category_id',
        'unit_model',
        'unit_number',
        'lokasi_kerja',
        'tanggal_penilaian',
        'shift',
        'criteria_scores',
        'score_p2h',
        'score_basic_control',
        'score_maneuver',
        'score_safety_k3',
        'total_score',
        'kesimpulan',
        'catatan_instruktur',
        'instruktur_signature_path',
        'status',
    ];

    protected $casts = [
        'tanggal_penilaian' => 'date',
        'criteria_scores' => 'array',
        'score_p2h' => 'float',
        'score_basic_control' => 'float',
        'score_maneuver' => 'float',
        'score_safety_k3' => 'float',
        'total_score' => 'float',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function equipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Eval2Certificate::class, 'assessment_id');
    }

    public function isKompeten(): bool
    {
        return $this->kesimpulan === 'kompeten';
    }
}
