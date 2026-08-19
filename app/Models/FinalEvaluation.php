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
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penilaian' => 'date',
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
}
