<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hki extends Model
{
    use HasFactory;

    protected $table = 'hkis';

    protected $fillable = [
        'dosen_id',
        'user_id',
        'judul_ciptaan',
        'jenis_ciptaan',
        'nomor_permohonan',
        'nomor_pencatatan',
        'status',
        'file_dokumen',
        'file_sertifikat',
        'keterangan',
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
