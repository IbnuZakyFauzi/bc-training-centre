<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OjtLogbook;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'sid',
        'name',
        'email',
        'password',
        'must_change_password',
        'is_super_admin',
        'role',
        'trainer_type',
        'phone',
        'avatar',
        'signature_path',
        'certification',
        'current_phase',
        'company',
        'department',
        'equipment_category_id',
        'equipment_number',
        'initial_hm_day',
        'initial_hm_night',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function equipmentCategory(): BelongsTo
    {
        return $this->belongsTo(EquipmentCategory::class);
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(OjtLogbook::class, 'trainee_id');
    }

    public function trainerLogbooks(): HasMany
    {
        return $this->hasMany(OjtLogbook::class, 'trainer_id');
    }

    public function isTrainee(): bool
    {
        return $this->role === 'trainee';
    }

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function assignedTrainers()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->select('users.*')
            ->withPivot('trainer_type')
            ->withTimestamps();
    }

    public function assignedInstruktur()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->select('users.*')
            ->wherePivot('trainer_type', 'instruktur');
    }

    public function assignedPengawas()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->select('users.*')
            ->wherePivot('trainer_type', 'pengawas');
    }

    public function assignedOperatorPendamping()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->select('users.*')
            ->wherePivot('trainer_type', 'operator_pendamping');
    }

    public function isInstruktur(): bool
    {
        return $this->role === 'trainer' && $this->trainer_type === 'instruktur';
    }

    public function isPengawas(): bool
    {
        return $this->role === 'trainer' && $this->trainer_type === 'pengawas';
    }

    public function isOperatorPendamping(): bool
    {
        return $this->role === 'trainer' && $this->trainer_type === 'operator_pendamping';
    }

    public function isTrainingCentre(): bool
    {
        return $this->role === 'admin';
    }

    public function isPjo(): bool
    {
        return $this->role === 'pjo';
    }

    public function isHseCt(): bool
    {
        return $this->role === 'hse_ct';
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin || $this->email === 'training.centre@beraucoal.co.id';
    }

    public function usesStoredSignature(): bool
    {
        return in_array($this->role, ['trainer', 'admin'], true);
    }

    public function phaseHistories()
    {
        return $this->hasMany(\App\Models\TraineePhaseHistory::class)->orderBy('created_at', 'desc');
    }

    public function currentPhaseKey(): string
    {
        return \App\Services\PhaseService::currentPhaseKey($this);
    }

    public function currentPhaseMeta(): ?array
    {
        return \App\Services\PhaseService::currentPhaseMeta($this);
    }

    public function hmProgress(): array
    {
        return \App\Services\PhaseService::hmProgress($this);
    }

    public function isPhaseEligible(?string $phase = null): bool
    {
        return \App\Services\PhaseService::isEligible($this, $phase);
    }

    public function isMonthlyEvaluationLocked(): bool
    {
        $phase = $this->currentPhaseKey();
        $meta = \App\Services\PhaseService::meta($this->certification ?? 'Green', $phase);

        if (!$meta) {
            return true;
        }

        if ($meta['type'] === 'evaluasi') {
            return !$this->isPhaseEligible($phase);
        }

        if ($meta['type'] === 'bulanan') {
            $approvedLogbookCount = OjtLogbook::where('trainee_id', $this->id)
                ->whereIn('status', ['verified', 'final_approved'])
                ->where('created_at', '>=', \App\Services\PhaseService::currentPhaseStartDate($this, $phase))
                ->count();

            return $approvedLogbookCount < 4;
        }

        return false;
    }

    public function phaseProgressPercent(?string $phase = null): array
    {
        return \App\Services\PhaseService::progressPercent($this, $phase);
    }
}
