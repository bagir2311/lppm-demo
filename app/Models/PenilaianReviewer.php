<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianReviewer extends Model
{
    use HasFactory;

    protected $table = 'penilaian_reviewers';

    protected $fillable = [
        'reviewer_id',
        'penelitian_id',
        'pengabdian_id',
        'skor_metodologi',
        'skor_relevansi',
        'skor_kelayakan',
        'total_skor',
        'rekomendasi',
        'catatan_reviewer',
        'status',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function penelitian(): BelongsTo
    {
        return $this->belongsTo(Penelitian::class, 'penelitian_id');
    }

    public function pengabdian(): BelongsTo
    {
        return $this->belongsTo(Pengabdian::class, 'pengabdian_id');
    }
}
