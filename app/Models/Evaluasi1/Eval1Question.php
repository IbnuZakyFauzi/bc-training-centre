<?php

namespace App\Models\Evaluasi1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eval1Question extends Model
{
    use HasFactory;

    protected $table = 'eval1_questions';

    protected $fillable = [
        'exam_id',
        'question_text',
        'question_image',
        'options',
        'correct_answer',
        'score_weight',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'score_weight' => 'float',
        'order' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Eval1Exam::class, 'exam_id');
    }
}
