<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nidn',
        'nama',
        'prodi',
        'fakultas',
        'email',
        'slug',
        'kepakaran',
        'avatar',
        'is_reviewer',
    ];

    protected $casts = [
        'is_reviewer' => 'boolean',
    ];

    public function penelitians(): HasMany
    {
        return $this->hasMany(Penelitian::class, 'ketua_dosen_id');
    }

    public function pengabdians(): HasMany
    {
        return $this->hasMany(Pengabdian::class, 'ketua_dosen_id');
    }
}
