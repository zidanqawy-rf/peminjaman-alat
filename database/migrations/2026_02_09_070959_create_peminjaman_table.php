<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('peminjaman_items')) {
            Schema::create('peminjaman_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
                $table->foreignId('alat_id')->constrained('alats')->onDelete('cascade');
                $table->integer('jumlah');
                $table->string('kondisi_alat')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_items');
    }
};