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
     */
    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }

    /**
     * Relasi ke Alat (alias)
     */
    public function alat()
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }
}