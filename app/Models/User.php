<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'phone',
        'address',
        'section_id',
        'email_verified_at',
        'is_active',
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
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        return match ($panel->getId()) {
            'admin' => in_array($this->role, [
                UserRole::SUPER_ADMIN,
                UserRole::ADMIN_SEKSI,
            ], true),
            'pimpinan'=>$this->role === UserRole::PIMPINAN, 
            default => false,
        };
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ServiceApplication::class);
    }

    public function assignedApplications(): HasMany
    {
        return $this->hasMany(
            ServiceApplication::class,
            'assigned_admin_id',
        );
    }

    public function queues(): HasMany
    {
        return $this->hasMany(ServiceQueue::class);
    }

    public function kMeansRuns(): HasMany
    {
        return $this->hasMany(
            KMeansRun::class,
            'executed_by',
        );
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(UserRole::SUPER_ADMIN);
    }

    public function isAdminSeksi(): bool
    {
        return $this->hasRole(UserRole::ADMIN_SEKSI);
    }

    public function isPimpinan(): bool
    {
        return $this->hasRole(UserRole::PIMPINAN);
    }

    public function isMasyarakat(): bool
    {
        return $this->hasRole(UserRole::MASYARAKAT);
    }
}