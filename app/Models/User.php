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
        'nrp',
        'name',
        'email',
        'password',
        'role',
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

    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    public function isDepartmentOperation(): bool
    {
        return $this->role === 'department_ops';
    }

    public function isTrainingCentre(): bool
    {
        return $this->role === 'admin';
    }

    public function usesStoredSignature(): bool
    {
        return in_array($this->role, ['trainer', 'department_ops', 'admin'], true);
    }
}
