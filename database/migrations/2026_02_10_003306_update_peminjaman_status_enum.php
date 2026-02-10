<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update ENUM untuk menambahkan status 'dipinjam' dan 'pending'
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

    public function down(): void
    {
        // Kembalikan ke ENUM lama
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM(
            'menunggu',
            'disetujui',
            'ditolak',
            'dikembalikan'
        ) NOT NULL DEFAULT 'menunggu'");
    }
};