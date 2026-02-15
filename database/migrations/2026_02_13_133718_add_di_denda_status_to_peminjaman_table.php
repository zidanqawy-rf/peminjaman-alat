<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Cek apakah kolom sudah ada, jika belum baru tambahkan
            if (!Schema::hasColumn('peminjaman', 'kondisi_alat_kembali')) {
                $table->enum('kondisi_alat_kembali', ['baik', 'rusak', 'hilang'])
                    ->nullable()
                    ->after('kondisi_alat')
                    ->comment('Kondisi alat saat dikembalikan');
            }
            
            if (!Schema::hasColumn('peminjaman', 'catatan_kondisi')) {
                $table->text('catatan_kondisi')
                    ->nullable()
                    ->after('kondisi_alat_kembali')
                    ->comment('Catatan kondisi alat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman', 'kondisi_alat_kembali')) {
                $table->dropColumn('kondisi_alat_kembali');
            }
            
            if (Schema::hasColumn('peminjaman', 'catatan_kondisi')) {
                $table->dropColumn('catatan_kondisi');
            }
        });
    }
};