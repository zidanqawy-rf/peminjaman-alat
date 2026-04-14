<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanDenda extends Model
{
    protected $table    = 'pengaturan_denda';
    protected $fillable = [
        'tarif_per_hari',
        'keterangan',
        'nama_bank',
        'no_rekening',
        'atas_nama',
        'no_dana',
        'nama_dana',
    ];

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
            [
                'tarif_per_hari' => 5000,
                'keterangan'     => 'Tarif denda default',
                'nama_bank'      => 'BCA',
                'no_rekening'    => '1234567890',
                'atas_nama'      => 'Laboratorium XYZ',
                'no_dana'        => null,
                'nama_dana'      => null,
            ]
        )->tarif_per_hari;
    }

    /**
     * Ambil instance pengaturan aktif.
     */
    public static function aktif(): static
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'tarif_per_hari' => 5000,
                'keterangan'     => 'Tarif denda default',
                'nama_bank'      => 'BCA',
                'no_rekening'    => '1234567890',
                'atas_nama'      => 'Laboratorium XYZ',
                'no_dana'        => null,
                'nama_dana'      => null,
            ]
        );
    }
}