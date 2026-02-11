<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
    ];

    /**
     * Relasi ke Alat
     * GUNAKAN INI JIKA tabel alats memiliki kolom 'kategori' (string)
     * yang menyimpan NAMA kategori, bukan ID
     */
    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori', 'nama');
    }

    /**
     * Relasi ke Alat (alias)
     */
    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori', 'nama');
    }
}