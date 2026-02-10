<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    // Konstanta Status
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DIPINJAM = 'dipinjam';
    const STATUS_PENGAJUAN_PENGEMBALIAN = 'pengajuan_pengembalian';
    const STATUS_DIKEMBALIKAN = 'dikembalikan';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'user_id',
        'tool_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_kembali_actual',
        'surat_peminjaman',
        'status',
        'catatan',
        'kondisi_alat',
        'catatan_petugas',
        'alasan_penolakan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'date',
        'tanggal_kembali_actual' => 'datetime',
    ];

    // Helper methods
    public function isMenunggu()
    {
        return in_array($this->status, [self::STATUS_MENUNGGU, self::STATUS_PENDING]);
    }

    public function isDisetujui()
    {
        return $this->status === self::STATUS_DISETUJUI;
    }

    public function isDipinjam()
    {
        return $this->status === self::STATUS_DIPINJAM;
    }

    public function isPengajuanPengembalian()
    {
        return $this->status === self::STATUS_PENGAJUAN_PENGEMBALIAN;
    }

    public function isDikembalikan()
    {
        return $this->status === self::STATUS_DIKEMBALIKAN;
    }

    public function isDitolak()
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class, 'tool_id');
    }
}