<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinalEvaluation extends Model
{
    protected $table = 'ojt_final_evaluations';

    protected $fillable = [
        'ojt_logbook_id',
        'trainer_id',
        'nama_operator',
        'perusahaan',
        'lokasi_kerja',
        'jenis_unit_a2b',
        'jenis_sertifikasi',
        'instruktur',
        'operator_pendamping',
        'tanggal_penilaian',
        'tahap_penilaian',
        'sub_tahap',
        'sub_tahap_keterangan',
        'phase',
        'status',
        'p2h_status',
        'teknik_pengoperasian_status',
        'kepatuhan_status',
        'kedisiplinan_status',
        'kesimpulan',
        'catatan',
        'instruktur_signature_path',
        'pengawas_signature_path',
        'operator_pendamping_signature_path',
        'kabag_signature_path',
        'penanggung_jawab_signature_path',
        'hse_signature_path',
        'tc_approved_by',
        'tc_approved_at',
        'tc_notes',
        'pjo_approved_by',
        'pjo_approved_at',
        'pjo_notes',
        'hse_approved_by',
        'hse_approved_at',
        'hse_notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penilaian' => 'date',
            'tc_approved_at' => 'datetime',
            'pjo_approved_at' => 'datetime',
            'hse_approved_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(OjtLogbook::class, 'ojt_logbook_id');
    }

    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nama_operator', 'name');
    }

    public function tcApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tc_approved_by');
    }

    public function pjoApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pjo_approved_by');
    }

    public function hseApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hse_approved_by');
    }

    public function histories()
    {
        return $this->hasMany(TraineePhaseHistory::class, 'evaluation_id');
    }

    public function isPendingTc(): bool
    {
        return $this->status === 'submitted';
    }

    public function isPendingPjo(): bool
    {
        return $this->status === 'tc_approved';
    }

    public function isPendingHse(): bool
    {
        return $this->status === 'pjo_approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'hse_approved';
    }

    public function isEditableByTrainer(): bool
    {
        return in_array($this->status, ['submitted', 'rejected'], true);
    }
}
