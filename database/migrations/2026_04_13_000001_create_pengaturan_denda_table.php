<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_denda', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tarif_per_hari')->default(5000)->comment('Tarif denda dalam rupiah per hari keterlambatan');
            $table->string('keterangan')->nullable()->comment('Keterangan / catatan tarif denda');
            $table->timestamps();
        });

        // Seed nilai default agar langsung ada 1 baris
        DB::table('pengaturan_denda')->insert([
            'tarif_per_hari' => 5000,
            'keterangan'     => 'Tarif denda default',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_denda');
    }
};