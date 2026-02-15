<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update ENUM untuk kolom status di tabel peminjaman
        // Tambahkan 'di_denda' ke list status yang ada
        
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'menunggu',
            'pending', 
            'disetujui', 
            'dipinjam', 
            'pengajuan_pengembalian', 
            'di_denda',
            'dikembalikan', 
            'selesai', 
            'ditolak'
        ) NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM lama (tanpa 'di_denda')
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'menunggu',
            'pending', 
            'disetujui', 
            'dipinjam', 
            'pengajuan_pengembalian', 
            'dikembalikan', 
            'selesai', 
            'ditolak'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};