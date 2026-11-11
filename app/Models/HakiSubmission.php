<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HakiSubmission extends Model
{
    use HasFactory;

    protected $table = 'haki_submissions';

    protected $fillable = [
        'dosen_id',
        'user_id',
        'judul_ciptaan',
        'jenis_ciptaan',
        'nama_ketua',
        'nama_anggota',
        'data_pencipta',
        'file_ktp',
        'file_surat_pernyataan',
        'file_surat_pengalihan',
        'file_lampiran_karya',
        'file_bukti_pembayaran',
        'status',
        'catatan_admin',
        'nomor_pencatatan',
        'file_sertifikat',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
