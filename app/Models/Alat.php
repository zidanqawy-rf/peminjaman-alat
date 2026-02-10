<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return Storage::url($this->gambar);
        }
        return asset('images/no-image.png'); // default image
    }

    // Hapus gambar lama saat update/delete
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