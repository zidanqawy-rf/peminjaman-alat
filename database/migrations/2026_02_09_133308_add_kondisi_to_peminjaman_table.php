<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambah kolom untuk approval/reject
            if (!Schema::hasColumn('peminjaman', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('catatan');
            }
            
            // Tambah kolom kondisi alat dan catatan petugas (sudah ada di model)
            if (!Schema::hasColumn('peminjaman', 'kondisi_alat')) {
                $table->string('kondisi_alat')->nullable()->after('alasan_penolakan');
            }
            
            if (!Schema::hasColumn('peminjaman', 'catatan_petugas')) {
                $table->text('catatan_petugas')->nullable()->after('kondisi_alat');
            }
        });

        // Update enum status untuk menyesuaikan dengan kode sebelumnya
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending', 'menunggu', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'selesai') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'kondisi_alat', 'catatan_petugas']);
        });
    }
};