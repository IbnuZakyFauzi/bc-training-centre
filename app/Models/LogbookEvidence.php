<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookEvidence extends Model
{
    use HasFactory;

    protected $table = 'logbook_evidences';

    protected $fillable = [
        'ojt_logbook_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(OjtLogbook::class, 'ojt_logbook_id');
    }
}
