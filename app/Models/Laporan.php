<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'proposal_id',
        'dosen_id',
        'fase_pelaporan',
        'file_laporan_substansi',
        'file_laporan_keuangan',
        'file_dokumentasi',
        'pilihan_luaran',
        'url_luaran',
        'file_luaran',
        'status',
        'catatan',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
