<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetencyEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ojt_logbook_id', 'trainer_id', 'overall_score', 'competency_status',
        'assessment_payload', 'trainer_comment', 'revision_instruction', 'trainer_signature_path',
        'evaluated_at', 'sent_to_pjo_at',
    ];

    protected $casts = [
        'assessment_payload' => 'array',
        'evaluated_at' => 'datetime',
        'sent_to_pjo_at' => 'datetime',
    ];

    public function logbook(): BelongsTo { return $this->belongsTo(OjtLogbook::class, 'ojt_logbook_id'); }
    public function trainer(): BelongsTo { return $this->belongsTo(User::class); }
}
