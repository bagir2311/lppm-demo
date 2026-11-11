<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosens';

    protected $fillable = [
        'user_id',
        'nidn',
        'nama',
        'prodi',
        'fakultas',
        'email',
        'slug',
        'kepakaran',
        'avatar',
        'status',
        'is_active',
        'is_reviewer',
        'bidang_garap',
        'kontak_kolaborasi',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_reviewer' => 'boolean',
    ];

    protected $appends = [
        'name',
        'faculty',
        'study_program',
        'expertise',
        'publications',
        'citations',
        'scholar_url',
        'sinta_url',
        'scopus_url',
        'research_topics',
        'research_history',
        'service_history',
        'selected_publications',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    public function getFacultyAttribute(): ?string
    {
        return $this->fakultas;
    }

    public function getStudyProgramAttribute(): ?string
    {
        return $this->prodi;
    }

    public function getExpertiseAttribute(): ?string
    {
        return $this->kepakaran ?? 'Bidang Akademik';
    }

    public function getPublicationsAttribute(): string
    {
        return '0';
    }

    public function getCitationsAttribute(): string
    {
        return '0';
    }

    public function getScholarUrlAttribute(): string
    {
        return '#';
    }

    public function getSintaUrlAttribute(): string
    {
        return '#';
    }

    public function getScopusUrlAttribute(): string
    {
        return '#';
    }

    public function getResearchTopicsAttribute(): array
    {
        return ['Riset Bidang ' . ($this->kepakaran ?? 'Akademik')];
    }

    public function getResearchHistoryAttribute(): array
    {
        return [
            ['year' => date('Y'), 'title' => 'Riset Evaluasi tentang ' . ($this->kepakaran ?? 'Keahlian Bidang'), 'role' => 'Ketua Peneliti', 'funding_source' => 'Hibah Internal Mahardika']
        ];
    }

    public function getServiceHistoryAttribute(): array
    {
        return [
            ['year' => date('Y'), 'title' => 'Pemberdayaan Masyarakat berbasis ' . ($this->kepakaran ?? 'Keahlian Bidang'), 'role' => 'Ketua Pengmas', 'funding_source' => 'Masyarakat / Mandiri']
        ];
    }

    public function getSelectedPublicationsAttribute(): array
    {
        return [
            ['year' => date('Y'), 'title' => 'Jurnal Kajian Teoretis ' . ($this->kepakaran ?? 'Keahlian Bidang'), 'journal' => 'Jurnal Mahardika', 'citation' => '0']
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penelitians(): HasMany
    {
        return $this->hasMany(Penelitian::class, 'ketua_dosen_id');
    }

    public function pengabdians(): HasMany
    {
        return $this->hasMany(Pengabdian::class, 'ketua_dosen_id');
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'dosen_id');
    }

    public function penugasanReviews(): HasMany
    {
        return $this->hasMany(PenugasanReview::class, 'reviewer_id');
    }
}
