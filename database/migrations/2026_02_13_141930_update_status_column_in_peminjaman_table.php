<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Ubah dari ENUM ke VARCHAR
            $table->string('status')->change();
            
            // Atau jika tetap pakai ENUM, tambahkan nilai yang kurang
            // $table->enum('status', [
            //     'menunggu', 'pending', 'disetujui', 'ditolak',
            //     'dipinjam', 'pengajuan_pengembalian', 'di_denda', 'dikembalikan'
            // ])->change();
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan'])->change();
        });
    }
};