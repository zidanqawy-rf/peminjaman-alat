<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'foto_pengembalian')) {
                $table->string('foto_pengembalian')->nullable()->after('surat_peminjaman');
            }
            if (!Schema::hasColumn('peminjaman', 'denda')) {
                $table->integer('denda')->default(0)->after('catatan_petugas');
            }
            if (!Schema::hasColumn('peminjaman', 'jumlah_hari_terlambat')) {
                $table->integer('jumlah_hari_terlambat')->default(0)->after('denda');
            }
            if (!Schema::hasColumn('peminjaman', 'bukti_pembayaran_denda')) {
                $table->string('bukti_pembayaran_denda')->nullable()->after('jumlah_hari_terlambat');
            }
            if (!Schema::hasColumn('peminjaman', 'status_pembayaran_denda')) {
                $table->enum('status_pembayaran_denda', ['belum_bayar', 'menunggu_verifikasi', 'terverifikasi'])
                      ->default('belum_bayar')
                      ->after('bukti_pembayaran_denda');
            }
            if (!Schema::hasColumn('peminjaman', 'catatan_admin_pembayaran')) {
                $table->text('catatan_admin_pembayaran')->nullable()->after('status_pembayaran_denda');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $cols = ['foto_pengembalian','denda','jumlah_hari_terlambat',
                     'bukti_pembayaran_denda','status_pembayaran_denda','catatan_admin_pembayaran'];
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('peminjaman', $c));
            if ($existing) $table->dropColumn(array_values($existing));
        });
    }
};