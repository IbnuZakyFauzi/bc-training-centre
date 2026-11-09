<?php

namespace App\Models\Evaluasi1;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eval1Attendance extends Model
{
    use HasFactory;

    protected $table = 'eval1_attendances';

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'total_hours',
        'topic',
        'status',
        'selfie_path',
        'location',
        'notes',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_hours' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function getFormattedHoursAttribute(): string
    {
        return number_format($this->total_hours, 1) . ' Jam';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'hadir' => ['label' => 'Hadir', 'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'izin' => ['label' => 'Izin', 'bg' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'sakit' => ['label' => 'Sakit', 'bg' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->status), 'bg' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
