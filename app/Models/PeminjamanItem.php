<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeminjamanItem extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_items';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'jumlah',
        'kondisi_alat',
        'catatan',
    ];

    // ── RELATIONSHIPS ────────────────────────────────────
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}