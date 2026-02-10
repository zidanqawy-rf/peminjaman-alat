<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'menunggu',
            'pending',
            'disetujui',
            'dipinjam',
            'pengajuan_pengembalian',
            'dikembalikan',
            'ditolak',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'menunggu',
            'pending',
            'disetujui',
            'dipinjam',
            'dikembalikan',
            'ditolak',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};