<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_denda', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaturan_denda', 'nama_bank')) {
                $table->string('nama_bank')->default('BCA')->after('keterangan');
            }
            if (!Schema::hasColumn('pengaturan_denda', 'no_rekening')) {
                $table->string('no_rekening')->default('1234567890')->after('nama_bank');
            }
            if (!Schema::hasColumn('pengaturan_denda', 'atas_nama')) {
                $table->string('atas_nama')->default('Laboratorium XYZ')->after('no_rekening');
            }
            if (!Schema::hasColumn('pengaturan_denda', 'no_dana')) {
                $table->string('no_dana')->nullable()->after('atas_nama');
            }
            if (!Schema::hasColumn('pengaturan_denda', 'nama_dana')) {
                $table->string('nama_dana')->nullable()->after('no_dana');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_denda', function (Blueprint $table) {
            $table->dropColumn(
                array_filter(
                    ['nama_bank', 'no_rekening', 'atas_nama', 'no_dana', 'nama_dana'],
                    fn($col) => Schema::hasColumn('pengaturan_denda', $col)
                )
            );
        });
    }
};