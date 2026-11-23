<?php

namespace App\Models;

use App\Models\LogbookAssignment;
use App\Services\PhaseService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OjtLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'logbook_number',
        'trainee_id',
        'trainer_id',
        'equipment_category_id',
        'equipment_id',
        'equipment_number',
        'date',
        'shift',
        'location',
        'hm_start',
        'hm_end',
        'total_hm',
        'sop_payload',
        'status',
        'revision_notes',
        'submitted_at',
        'verified_at',
        'approved_at',
        'pjo_id',
        'pjo_notes',
        'pjo_decided_at',
        'training_centre_id',
        'training_centre_notes',
        'training_centre_decided_at',
        'trainer_signature_path',
        'pjo_signature_path',
        'training_centre_signature_path',
        'assigned_pjo_id',
        'assigned_tc_id',
        'selected_pengawas_ids',
        'selected_operator_pendamping_ids',
    ];

    protected $casts = [
        'date' => 'date',
        'hm_start' => 'decimal:1',
        'hm_end' => 'decimal:1',
        'total_hm' => 'decimal:1',
        'hm_day' => 'decimal:1',
        'hm_night' => 'decimal:1',
        'sop_payload' => 'array',
        'selected_pengawas_ids' => 'array',
        'selected_operator_pendamping_ids' => 'array',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'pjo_decided_at' => 'datetime',
        'training_centre_decided_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function pengawasTrainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pjo_id');
    }

    public function trainingCentre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'training_centre_id');
    }

    public function assignedPjo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_pjo_id');
    }

    public function assignedTc(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_tc_id');
    }



    public function equipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class)->withDefault([
            'unit_code' => '-',
        ]);
    }

    public function getUnitCodeAttribute()
    {
        return $this->equipment_number
            ?? data_get($this->sop_payload, 'meta.equipment_number')
            ?? $this->equipment->unit_code
            ?? '-';
    }

    public function histories(): HasMany
    {
        return $this->hasMany(LogbookHistory::class)->orderBy('created_at', 'desc');
    }

    public function evaluation()
    {
        return $this->hasOne(CompetencyEvaluation::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LogbookAssignment::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(LogbookEvidence::class, 'ojt_logbook_id');
    }

    public function getPhaseKeyAttribute(): string
    {
        $phase = data_get($this->sop_payload, 'meta.phase');
        if ($phase) {
            return $phase;
        }
        $trainee = $this->trainee;
        if ($trainee) {
            $mode = data_get($this->sop_payload, 'meta.assessment_mode');
            $stage = data_get($this->sop_payload, 'meta.assessment_stage');
            $detail = data_get($this->sop_payload, 'meta.assessment_stage_detail');
            if ($mode === 'tanpa_pendampingan' && $stage === 'bulanan') {
                if ($detail && preg_match('/bulan_(\d+)/', $detail, $m)) {
                    return 'bulanan_' . ((int) $m[1] + 4);
                }
                return 'bulanan_5';
            }
            return $trainee->current_phase ?? PhaseService::firstPhase($trainee->certification ?? 'Green');
        }
        return 'evaluasi_3';
    }

    public function getPhaseLabelAttribute(): string
    {
        $key = $this->phase_key;
        $cert = $this->trainee?->certification ?? 'Green';
        $meta = PhaseService::meta($cert, $key);
        return $meta['label'] ?? ucwords(str_replace('_', ' ', $key));
    }
}
