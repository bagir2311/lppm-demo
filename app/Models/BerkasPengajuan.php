<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BerkasPengajuan extends Model
{
    use HasFactory;

    protected $table = 'berkas_pengajuans';

    protected $fillable = [
        'user_id',
        'dosen_id',
        'penelitian_id',
        'pengabdian_id',
        'jenis_berkas',
        'nama_file',
        'file_path',
        'ukuran_file',
        'status',
        'catatan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
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
