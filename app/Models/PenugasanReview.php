<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanReview extends Model
{
    use HasFactory;

    protected $table = 'penugasan_reviews';

    protected $fillable = [
        'proposal_id',
        'reviewer_id',
        'nilai',
        'catatan_revisi',
        'status_penilaian',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    /**
     * Relasi ke model Proposal.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    /**
     * Relasi ke model Dosen (ID Dosen penilai).
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'reviewer_id');
    }

    /**
     * Alias relasi ke model Dosen.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'reviewer_id');
    }

    /**
     * Relasi ke model User (jika reviewer dipetakan ke User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
