<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // Mengizinkan kolom-kolom ini untuk diisi data dari formulir
    protected $fillable = ['user_id', 'judul', 'jenis', 'file_proposal', 'status'];

    // Relasi balik ke tabel User (Dosen pengusul)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}