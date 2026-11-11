<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';

    protected $fillable = [
        'nama_periode',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
    ];

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'periode_id');
    }
}
