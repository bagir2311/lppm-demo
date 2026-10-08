<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penelitian extends Model
{
    use HasFactory;

    protected $table = 'penelitians';

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
    ];

    public function ketuaDosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'ketua_dosen_id');
    }
}
