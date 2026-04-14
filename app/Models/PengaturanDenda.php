<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanDenda extends Model
{
    protected $table    = 'pengaturan_denda';
    protected $fillable = ['tarif_per_hari', 'keterangan'];

    protected $casts = [
        'tarif_per_hari' => 'integer',
    ];

    /**
     * Ambil tarif aktif (selalu baris pertama / id=1).
     * Jika belum ada, buat dengan default 5000.
     */
    public static function tarif(): int
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['tarif_per_hari' => 5000, 'keterangan' => 'Tarif denda default']
        )->tarif_per_hari;
    }

    /**
     * Ambil instance pengaturan aktif.
     */
    public static function aktif(): static
    {
        return static::firstOrCreate(
            ['id' => 1],
            ['tarif_per_hari' => 5000, 'keterangan' => 'Tarif denda default']
        );
    }
}