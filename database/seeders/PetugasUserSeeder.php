<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PetugasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing petugas@gmail.com
        User::where('email', 'petugas@gmail.com')->delete();

        // Create new petugas user
        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('Petugas123'),
            'role' => 'petugas',
            'email_verified_at' => now(),
        ]);
    }
}
