<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus admin yang sudah ada
        User::where('email', 'admin@gmail.com')->delete();

        // Buat admin baru
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);

        $this->command->info('Admin user (admin@gmail.com) berhasil dibuat!');
    }
}
