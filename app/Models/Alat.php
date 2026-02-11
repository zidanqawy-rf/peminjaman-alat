<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alats';

    protected $fillable = [
        'kode',
        'nama',
        'nama_alat',
        'kategori_id',
        'kategori',
        'spesifikasi',
        'jumlah',
        'satuan',
        'kondisi',
        'status',
        'lokasi',
        'gambar',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    /**
     * Relasi ke Kategori (jika menggunakan tabel kategori)
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke Peminjaman
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'tool_id');
    }

    /**
     * Scope untuk alat yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')
                     ->where('jumlah', '>', 0);
    }

    /**
     * Scope untuk alat berdasarkan kategori
     */
    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Accessor untuk nama alat (support both nama and nama_alat)
     */
    public function getNamaAlatAttribute($value)
    {
        return $value ?? $this->attributes['nama'] ?? null;
    }

    /**
     * Check apakah alat tersedia
     */
    public function isTersedia()
    {
        return $this->status === 'tersedia' && $this->jumlah > 0;
    }
}