<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ojt_logbook_id',
        'user_id',
        'role_type',
        'status',
        'notes',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function logbook(): BelongsTo
    {
        return $this->belongsTo(OjtLogbook::class, 'ojt_logbook_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
