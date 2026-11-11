<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surats';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat',
        'nama_dosen',
        'nidn',
        'prodi',
        'keperluan',
        'instansi_tujuan',
        'anggota',
        'status',
        'qr_code_hash',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'anggota' => 'array',
    ];

    /**
     * Accessor judul_riset (alias keperluan).
     */
    public function getJudulRisetAttribute()
    {
        return $this->keperluan;
    }

    /**
     * Accessor anggota (array of anggota).
     */
    public function getAnggotaAttribute($value)
    {
        if ($value) {
            $decoded = is_string($value) ? json_decode($value, true) : $value;
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }

        return [
            [
                'nama' => $this->nama_dosen,
                'judul' => $this->keperluan,
            ]
        ];
    }
}
