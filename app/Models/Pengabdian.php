<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengabdian extends Model
{
    use HasFactory;

    protected $table = 'pengabdians';

    protected $fillable = [
        'judul',
        'abstrak',
        'tahun_pelaksanaan',
        'skema',
        'bidang_fokus',
        'ketua_dosen_id',
        'status',
        'file_pdf_url',
        'dana_disetujui',
        'lokasi_mitra',
    ];

    public function ketuaDosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'ketua_dosen_id');
    }
}
