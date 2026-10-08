<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CompetencyEvaluation;
use App\Models\FinalEvaluation;
use App\Models\LogbookAssignment;
use App\Models\LogbookEvidence;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\TraineePhaseHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            // 1. Hapus file tanda tangan & avatar fisik pengguna jika ada
            if ($user->signature_path && Storage::disk('public')->exists($user->signature_path)) {
                Storage::disk('public')->delete($user->signature_path);
            }
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 2. Lepas relasi penugasan trainer & trainee
            $user->assignedTrainers()->detach();
            DB::table('trainee_trainer')
                ->where('trainer_id', $user->id)
                ->orWhere('trainee_id', $user->id)
                ->delete();

            // 3. Hapus histori fase trainee
            TraineePhaseHistory::where('user_id', $user->id)->delete();

            // 4. Hapus seluruh Form OJT Logbook & data/dokumen pendukungnya
            $logbooks = OjtLogbook::where('trainee_id', $user->id)->get();
            $logbookIds = $logbooks->pluck('id')->toArray();

            if (!empty($logbookIds)) {
                // Hapus file evidence fisik jika ada
                $evidences = LogbookEvidence::whereIn('ojt_logbook_id', $logbookIds)->get();
                foreach ($evidences as $evidence) {
                    if ($evidence->file_path && Storage::disk('public')->exists($evidence->file_path)) {
                        Storage::disk('public')->delete($evidence->file_path);
                    }
                }
                LogbookEvidence::whereIn('ojt_logbook_id', $logbookIds)->delete();

                // Hapus evaluasi kompetensi & tanda tangan logbook
                CompetencyEvaluation::whereIn('ojt_logbook_id', $logbookIds)->delete();
                LogbookHistory::whereIn('ojt_logbook_id', $logbookIds)->delete();
                LogbookAssignment::whereIn('ojt_logbook_id', $logbookIds)->delete();

                // Hapus evaluasi final yang terikat dengan logbook-logbook ini
                $evalsFromLogbooks = FinalEvaluation::whereIn('ojt_logbook_id', $logbookIds)->get();
                foreach ($evalsFromLogbooks as $eval) {
                    TraineePhaseHistory::where('evaluation_id', $eval->id)->delete();
                    $eval->delete();
                }

                // Hapus data logbook
                OjtLogbook::whereIn('id', $logbookIds)->delete();
            }

            // 5. Hapus Form Evaluasi Final & Dokumen yang bersangkutan dengan nama trainee
            if ($user->role === 'trainee') {
                $finalEvaluations = FinalEvaluation::where('nama_operator', $user->name)->get();
                foreach ($finalEvaluations as $eval) {
                    TraineePhaseHistory::where('evaluation_id', $eval->id)->delete();
                    $eval->delete();
                }
            }

            // 6. Hapus notifikasi yang bersangkutan
            DB::table('notifications')->where('notifiable_id', $user->id)->delete();
        });
    }

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

        return !$this->isPhaseEligible($phase);
    }

    public function phaseProgressPercent(?string $phase = null): array
    {
        return \App\Services\PhaseService::progressPercent($this, $phase);
    }
}
