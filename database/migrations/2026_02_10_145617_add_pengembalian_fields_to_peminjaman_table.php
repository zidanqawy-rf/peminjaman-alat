<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Foto dokumentasi saat pengembalian
            $table->string('foto_pengembalian')->nullable()->after('surat_peminjaman');
            
            // Denda
            $table->integer('denda')->default(0)->after('catatan_petugas');
            $table->integer('jumlah_hari_terlambat')->default(0)->after('denda');
            
            // Bukti pembayaran denda
            $table->string('bukti_pembayaran_denda')->nullable()->after('jumlah_hari_terlambat');
            $table->enum('status_pembayaran_denda', ['belum_bayar', 'menunggu_verifikasi', 'terverifikasi'])->default('belum_bayar')->after('bukti_pembayaran_denda');
            
            // Catatan admin untuk pembayaran
            $table->text('catatan_admin_pembayaran')->nullable()->after('status_pembayaran_denda');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn([
                'foto_pengembalian',
                'denda',
                'jumlah_hari_terlambat',
                'bukti_pembayaran_denda',
                'status_pembayaran_denda',
                'catatan_admin_pembayaran'
            ]);
        });
    }
};