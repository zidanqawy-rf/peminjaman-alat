<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'tool_id',  // Keep this for backward compatibility
        'alat_id',  // Add this
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_kembali_actual',
        'surat_peminjaman',
        'status',
        'catatan',
        'keperluan',
        'kondisi_alat',
        'catatan_petugas',
        'alasan_penolakan',
        // Kolom untuk pengembalian dan denda
        'foto_pengembalian',
        'denda',
        'jumlah_hari_terlambat',
        'bukti_pembayaran_denda',
        'status_pembayaran_denda',
        'catatan_admin_pembayaran',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'date',
        'tanggal_kembali_actual' => 'datetime',
        'denda' => 'integer',
        'jumlah_hari_terlambat' => 'integer',
    ];

    protected $appends = ['hari_terlambat'];

    /**
     * ==========================================
     * RELATIONSHIPS
     * ==========================================
     */
    
    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * CRITICAL FIX: Relasi ke Alat
     * HARUS spesifikasi 'tool_id' sebagai foreign key
     * Karena nama kolom di database adalah 'tool_id' bukan 'alat_id'
     */
    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'tool_id', 'id');
    }

    /**
     * ==========================================
     * HELPER METHODS UNTUK STATUS
     * ==========================================
     */
    
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

    /**
     * ==========================================
     * ACCESSORS & MUTATORS
     * ==========================================
     */
    
    /**
     * Accessor: Hitung hari terlambat
     * Bisa diakses dengan $peminjaman->hari_terlambat
     */
    public function getHariTerlambatAttribute()
    {
        // Cek jika sudah ada di database
        if (isset($this->attributes['jumlah_hari_terlambat']) && $this->attributes['jumlah_hari_terlambat'] > 0) {
            return $this->attributes['jumlah_hari_terlambat'];
        }

        // Jika belum ada tanggal kembali actual, return 0
        if (!$this->tanggal_kembali_actual) {
            return 0;
        }

        // Hitung dari tanggal
        return $this->hitungHariTerlambat();
    }

    /**
     * ==========================================
     * BUSINESS LOGIC METHODS
     * ==========================================
     */
    
    /**
     * Hitung denda berdasarkan keterlambatan
     * Denda: Rp 5.000/hari
     */
    public function hitungDenda(): int
    {
        if (!$this->tanggal_kembali || !$this->tanggal_kembali_actual) {
            return 0;
        }

        // Parse tanggal untuk memastikan Carbon instance
        $tanggalKembaliRencana = $this->tanggal_kembali instanceof Carbon 
            ? $this->tanggal_kembali 
            : Carbon::parse($this->tanggal_kembali);
            
        $tanggalKembaliActual = $this->tanggal_kembali_actual instanceof Carbon
            ? $this->tanggal_kembali_actual
            : Carbon::parse($this->tanggal_kembali_actual);

        // Reset ke awal hari untuk perhitungan yang akurat
        $tanggalKembaliRencana = $tanggalKembaliRencana->copy()->startOfDay();
        $tanggalKembaliActual = $tanggalKembaliActual->copy()->startOfDay();
        
        // Hitung selisih hari (jika actual > rencana = terlambat)
        if ($tanggalKembaliActual->gt($tanggalKembaliRencana)) {
            $hariTerlambat = $tanggalKembaliActual->diffInDays($tanggalKembaliRencana);
            $dendaPerHari = 5000; // Rp 5.000 per hari
            return $hariTerlambat * $dendaPerHari;
        }

        return 0;
    }

    /**
     * Hitung jumlah hari terlambat
     */
    public function hitungHariTerlambat(): int
    {
        if (!$this->tanggal_kembali || !$this->tanggal_kembali_actual) {
            return 0;
        }

        // Parse tanggal untuk memastikan Carbon instance
        $tanggalKembaliRencana = $this->tanggal_kembali instanceof Carbon 
            ? $this->tanggal_kembali 
            : Carbon::parse($this->tanggal_kembali);
            
        $tanggalKembaliActual = $this->tanggal_kembali_actual instanceof Carbon
            ? $this->tanggal_kembali_actual
            : Carbon::parse($this->tanggal_kembali_actual);

        // Reset ke awal hari
        $tanggalKembaliRencana = $tanggalKembaliRencana->copy()->startOfDay();
        $tanggalKembaliActual = $tanggalKembaliActual->copy()->startOfDay();
        
        // Hitung selisih hari (jika actual > rencana = terlambat)
        if ($tanggalKembaliActual->gt($tanggalKembaliRencana)) {
            return $tanggalKembaliActual->diffInDays($tanggalKembaliRencana);
        }

        return 0;
    }

    /**
     * Check apakah peminjaman sudah terlambat (berdasarkan hari ini)
     */
    public function isOverdue(): bool
    {
        if ($this->status !== self::STATUS_DIPINJAM) {
            return false;
        }

        return now()->isAfter($this->tanggal_kembali);
    }

    /**
     * ==========================================
     * QUERY SCOPES
     * ==========================================
     */
    
    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk peminjaman yang aktif
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DISETUJUI, 
            self::STATUS_DIPINJAM,
            self::STATUS_PENGAJUAN_PENGEMBALIAN
        ]);
    }

    /**
     * Scope untuk peminjaman yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DIKEMBALIKAN, 
            self::STATUS_DITOLAK,
            self::STATUS_SELESAI
        ]);
    }
}