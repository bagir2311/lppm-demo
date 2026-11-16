<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecturer extends Model
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
        'bidang_garap',
        'kontak_kolaborasi',
        'scholar_url',
        'sinta_url',
        'scopus_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function getInternalPublicationsCount(): int
    {
        if (!$this->exists || !$this->id) {
            return 0;
        }

        $total = 0;

        try {
            // 1. Penelitian (tabel penelitians)
            if (\Illuminate\Support\Facades\Schema::hasTable('penelitians')) {
                $total += \Illuminate\Support\Facades\DB::table('penelitians')
                    ->where('ketua_dosen_id', $this->id)
                    ->count();
            }

            // 2. Pengabdian (tabel pengabdians)
            if (\Illuminate\Support\Facades\Schema::hasTable('pengabdians')) {
                $total += \Illuminate\Support\Facades\DB::table('pengabdians')
                    ->where('ketua_dosen_id', $this->id)
                    ->count();
            }

            // 3. Proposal Penelitian / Pengabdian (tabel proposals)
            if (\Illuminate\Support\Facades\Schema::hasTable('proposals')) {
                $total += \Illuminate\Support\Facades\DB::table('proposals')
                    ->where('dosen_id', $this->id)
                    ->count();
            }

            // 4. Hak Kekayaan Intelektual (tabel haki_submissions & hkis)
            if (\Illuminate\Support\Facades\Schema::hasTable('haki_submissions')) {
                $total += \Illuminate\Support\Facades\DB::table('haki_submissions')
                    ->where('dosen_id', $this->id)
                    ->count();
            }
            if (\Illuminate\Support\Facades\Schema::hasTable('hkis')) {
                $total += \Illuminate\Support\Facades\DB::table('hkis')
                    ->where('dosen_id', $this->id)
                    ->count();
            }

            // 5. Laporan LPJ Luaran (tabel laporans)
            if (\Illuminate\Support\Facades\Schema::hasTable('laporans')) {
                $total += \Illuminate\Support\Facades\DB::table('laporans')
                    ->where('dosen_id', $this->id)
                    ->whereNotNull('url_luaran')
                    ->count();
            }
        } catch (\Throwable $e) {}

        return $total;
    }

    public function getPublicationsAttribute(): string
    {
        try {
            $internalCount = $this->getInternalPublicationsCount();
            if ($internalCount > 0) {
                return (string)$internalCount;
            }

            if (!empty($this->attributes['publications']) && (int)$this->attributes['publications'] > 0) {
                return (string)$this->attributes['publications'];
            }

            return '0';
        } catch (\Throwable $e) {
            return (string)($this->attributes['publications'] ?? '0');
        }
    }

    public function getCitationsAttribute(): string
    {
        return (string)($this->attributes['citations'] ?? '0');
    }

    public function getScholarUrlAttribute(): ?string
    {
        return $this->attributes['scholar_url'] ?? null;
    }

    public function getSintaUrlAttribute(): ?string
    {
        return $this->attributes['sinta_url'] ?? null;
    }

    public function getScopusUrlAttribute(): ?string
    {
        return $this->attributes['scopus_url'] ?? null;
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
}
