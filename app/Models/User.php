<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'department_id',
        'phone',
        'avatar',
        'signature_path',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
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

    public function assignedTrainers()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->withPivot('trainer_type')
            ->withTimestamps();
    }

    public function assignedInstruktur()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->wherePivot('trainer_type', 'instruktur');
    }

    public function assignedPengawas()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
            ->wherePivot('trainer_type', 'pengawas');
    }

    public function assignedOperatorPendamping()
    {
        return $this->belongsToMany(User::class, 'trainee_trainer', 'trainee_id', 'trainer_id')
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

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin || $this->email === 'training.centre@beraucoal.co.id';
    }

    public function usesStoredSignature(): bool
    {
        return in_array($this->role, ['trainer', 'admin'], true);
    }
}
