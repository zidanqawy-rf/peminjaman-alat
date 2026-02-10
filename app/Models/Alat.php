<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Alat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'jumlah',
        'status',
        'gambar',
    ];

    /**
     * ==========================================
     * RELATIONSHIPS
     * ==========================================
     */
    
    /**
     * Relasi ke Peminjaman
     * Satu alat bisa dipinjam berkali-kali
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'tool_id', 'id');
    }

    /**
     * Get peminjaman yang sedang aktif (dipinjam)
     */
    public function peminjamanAktif()
    {
        return $this->hasMany(Peminjaman::class, 'tool_id', 'id')
                    ->whereIn('status', ['disetujui', 'dipinjam', 'pengajuan_pengembalian']);
    }

    /**
     * ==========================================
     * ACCESSORS
     * ==========================================
     */
    
    /**
     * Accessor untuk URL gambar
     */
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return Storage::url($this->gambar);
        }
        return asset('images/no-image.png'); // default image
    }

    /**
     * ==========================================
     * SCOPES
     * ==========================================
     */
    
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
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk search alat
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
                     ->orWhere('kategori', 'like', "%{$search}%");
    }

    /**
     * ==========================================
     * HELPER METHODS
     * ==========================================
     */
    
    /**
     * Check apakah alat tersedia untuk dipinjam
     */
    public function isTersedia(): bool
    {
        return $this->status === 'tersedia' && $this->jumlah > 0;
    }

    /**
     * Get jumlah alat yang sedang dipinjam
     */
    public function getJumlahDipinjamAttribute(): int
    {
        return $this->peminjamanAktif()->sum('jumlah');
    }

    /**
     * Get jumlah alat yang tersisa (available)
     */
    public function getJumlahTersisaAttribute(): int
    {
        return $this->jumlah;
    }

    /**
     * ==========================================
     * BOOT METHOD
     * ==========================================
     */
    
    /**
     * Hapus gambar lama saat update/delete
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($alat) {
            if ($alat->isDirty('gambar') && $alat->getOriginal('gambar')) {
                Storage::delete($alat->getOriginal('gambar'));
            }
        });

        static::deleting(function ($alat) {
            if ($alat->gambar) {
                Storage::delete($alat->gambar);
            }
        });
    }
}