<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'dosen_id',
        'reviewer_id',
        'periode_id',
        'judul',
        'skema',
        'tahun',
        'abstrak',
        'jenis_hibah',
        'sub_kategori',
        'file_proposal',
        'file_rab',
        'status',
        'nilai_total',
        'catatan_reviewer',
        'rekomendasi',
    ];

    /**
     * Relasi ke Dosen pengusul.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    /**
     * Relasi ke seluruh PenugasanReview proposal ini.
     */
    public function penugasanReviews(): HasMany
    {
        return $this->hasMany(PenugasanReview::class, 'proposal_id');
    }

    /**
     * Relasi ke PenugasanReview tunggal.
     */
    public function penugasanReview(): HasOne
    {
        return $this->hasOne(PenugasanReview::class, 'proposal_id');
    }
}
