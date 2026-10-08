<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengajuan extends Model
{
    use HasFactory;

    protected $table = 'surat_pengajuans';

    protected $fillable = [
        'user_id',
        'dosen_id',
        'jenis_surat',
        'nomor_surat',
        'judul_penelitian',
        'lokasi_penelitian',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan_admin',
        'file_pdf_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
