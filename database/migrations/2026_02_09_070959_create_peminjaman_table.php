<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu, jika tabel belum ada baru buat
        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('tool_id')->constrained('alats')->onDelete('cascade');
                $table->integer('jumlah');
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali');
                $table->date('tanggal_kembali_actual')->nullable();
                $table->string('surat_peminjaman')->nullable();
                $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dikembalikan'])->default('menunggu');
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};