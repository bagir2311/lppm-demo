<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Dapatkan nilai pengaturan berdasarkan key.
     */
    public static function get(string $key, $default = null): ?string
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting && $setting->value !== null ? $setting->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Simpan atau perbarui nilai pengaturan.
     */
    public static function set(string $key, ?string $value): void
    {
        try {
            static::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        } catch (\Throwable $e) {
            // Ignore if DB issue
        }
    }

    /**
     * Ambil seluruh konfigurasi dalam bentuk key-value array.
     */
    public static function getAllAsArray(): array
    {
        try {
            return static::pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }
}
