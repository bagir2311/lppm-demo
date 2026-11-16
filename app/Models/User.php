<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_STAF = 'staf';
    const ROLE_DOSEN = 'dosen';
    const ROLE_REVIEWER = 'reviewer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dosen_id',
        'is_reviewer',
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
            'is_reviewer' => 'boolean',
        ];
    }

    public function lecturer(): HasOne
    {
        return $this->hasOne(Lecturer::class, 'user_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function penugasanReviews(): HasMany
    {
        return $this->hasMany(PenugasanReview::class, 'reviewer_id');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, 'admin_lppm']);
    }

    public function isStaf(): bool
    {
        return in_array($this->role, [self::ROLE_STAF, 'staff']);
    }

    public function isDosen(): bool
    {
        return $this->role === self::ROLE_DOSEN;
    }

    public function isReviewer(): bool
    {
        return $this->role === self::ROLE_REVIEWER || (bool)$this->is_reviewer;
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->isAdmin() || $this->isStaf();
    }

    public function getLinkedDosenAttribute()
    {
        if ($this->dosen_id && $this->dosen) {
            return $this->dosen;
        }
        if ($this->lecturer) {
            return $this->lecturer;
        }
        return Dosen::where('email', $this->email)->first() ?? Dosen::where('user_id', $this->id)->first();
    }
}
