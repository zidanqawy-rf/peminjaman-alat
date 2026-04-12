<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    const STATUS_MENUNGGU               = 'menunggu';
    const STATUS_DISETUJUI              = 'disetujui';
    const STATUS_DITOLAK                = 'ditolak';
    const STATUS_DIPINJAM               = 'dipinjam';
    const STATUS_PENGAJUAN_PENGEMBALIAN = 'pengajuan_pengembalian';
    const STATUS_DI_DENDA               = 'di_denda';
    const STATUS_DIKEMBALIKAN           = 'dikembalikan';
    const STATUS_SELESAI                = 'selesai';
    const STATUS_DIBATALKAN             = 'dibatalkan';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_kembali_actual',
        'surat_peminjaman',
        'foto_pengembalian',
        'status',
        'catatan',
        'keperluan',
        'kondisi_alat',
        'catatan_petugas',
        'alasan_penolakan',
        'denda',
        'jumlah_hari_terlambat',
        'bukti_pembayaran_denda',
        'status_pembayaran_denda',
        'catatan_admin_pembayaran',
    ];

    protected $casts = [
        'tanggal_pinjam'         => 'date',
        'tanggal_kembali'        => 'date',
        'tanggal_kembali_actual' => 'date',
        'denda'                  => 'integer',
        'jumlah_hari_terlambat'  => 'integer',
    ];

    // ── RELATIONSHIPS ────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * PERBAIKAN: tambah ->with('alat') agar accessor getNamaAlatSingkat
     * tidak error saat items di-load tanpa explicit eager-load
     */
    public function items(): HasMany
    {
        return $this->hasMany(PeminjamanItem::class, 'peminjaman_id')->with('alat');
    }

    // ── ACCESSORS ─────────────────────────────────────────

    public function getNamaAlatSingkatAttribute(): string
    {
        if ($this->items->isEmpty()) return 'N/A';

        $first = $this->items->first();
        $nama  = optional($first->alat)->nama ?? 'N/A';
        $total = $this->items->count();

        return $total > 1 ? "{$nama} +".($total - 1)." lainnya" : $nama;
    }

    public function getTotalUnitAttribute(): int
    {
        return $this->items->sum('jumlah');
    }

    // ── STATUS HELPERS ────────────────────────────────────

    public function isMenunggu(): bool        { return $this->status === self::STATUS_MENUNGGU; }
    public function isDisetujui(): bool       { return $this->status === self::STATUS_DISETUJUI; }
    public function isDipinjam(): bool        { return $this->status === self::STATUS_DIPINJAM; }
    public function isDiDenda(): bool         { return $this->status === self::STATUS_DI_DENDA; }
    public function isDikembalikan(): bool    { return $this->status === self::STATUS_DIKEMBALIKAN; }
    public function isDitolak(): bool         { return $this->status === self::STATUS_DITOLAK; }
    public function isDibatalkan(): bool      { return $this->status === self::STATUS_DIBATALKAN; }
    public function isSelesai(): bool         { return $this->status === self::STATUS_SELESAI; }

    public function isPengajuanPengembalian(): bool
    {
        return $this->status === self::STATUS_PENGAJUAN_PENGEMBALIAN;
    }

    public function isOverdue(): bool
    {
        if ($this->status !== self::STATUS_DIPINJAM) return false;
        return now()->startOfDay()->gt(Carbon::parse($this->tanggal_kembali)->startOfDay());
    }

    // ── BUSINESS LOGIC ────────────────────────────────────

    public function hitungDenda(): int
    {
        if (!$this->tanggal_kembali || !$this->tanggal_kembali_actual) return 0;

        $rencana = Carbon::parse($this->tanggal_kembali)->startOfDay();
        $actual  = Carbon::parse($this->tanggal_kembali_actual)->startOfDay();

        return $actual->gt($rencana) ? $rencana->diffInDays($actual) * 5000 : 0;
    }

    public function hitungHariTerlambat(): int
    {
        if (!$this->tanggal_kembali || !$this->tanggal_kembali_actual) return 0;

        $rencana = Carbon::parse($this->tanggal_kembali)->startOfDay();
        $actual  = Carbon::parse($this->tanggal_kembali_actual)->startOfDay();

        return $actual->gt($rencana) ? $rencana->diffInDays($actual) : 0;
    }

    // ── QUERY SCOPES ──────────────────────────────────────

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DISETUJUI,
            self::STATUS_DIPINJAM,
            self::STATUS_PENGAJUAN_PENGEMBALIAN,
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DIKEMBALIKAN,
            self::STATUS_DITOLAK,
            self::STATUS_SELESAI,
        ]);
    }
}