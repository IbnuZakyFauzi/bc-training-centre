<?php

namespace App\Models;

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
        'supervisor_id',
        'department_id',
        'equipment_category_id',
        'equipment_id',
        'date',
        'shift',
        'location',
        'start_time',
        'finish_time',
        'hm_start',
        'hm_end',
        'total_hm',
        'daily_activity',
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
    ];

    protected $casts = [
        'date' => 'date',
        'hm_start' => 'decimal:1',
        'hm_end' => 'decimal:1',
        'total_hm' => 'decimal:1',
        'sop_payload' => 'array',
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

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function departmentOperation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pjo_id');
    }

    public function trainingCentre(): BelongsTo
    {
        return $this->belongsTo(User::class, 'training_centre_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function equipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(LogbookEvidence::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(LogbookHistory::class)->orderBy('created_at', 'desc');
    }

    public function evaluation()
    {
        return $this->hasOne(CompetencyEvaluation::class);
    }
}
