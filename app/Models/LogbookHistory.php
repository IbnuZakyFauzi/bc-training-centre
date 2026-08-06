<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ojt_logbook_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'comment',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(OjtLogbook::class, 'ojt_logbook_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
