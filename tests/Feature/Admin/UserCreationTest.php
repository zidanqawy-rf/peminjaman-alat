<?php

use App\Models\User;

test('admin can create user with role user', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->post(route('admin.register.post'), [
            'name' => 'User Baru',
            'email' => 'user@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'user',
        ]);

    $response->assertRedirectToRoute('admin.users.index');
    $this->assertDatabaseHas('users', [
        'name' => 'User Baru',
        'email' => 'user@test.com',
        'role' => 'user',
    ]);
});

test('admin can create petugas from user form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->post(route('admin.register.post'), [
            'name' => 'Petugas Baru',
            'email' => 'petugasbaru@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'petugas',
        ]);

    $response->assertRedirectToRoute('admin.users.index');
    $this->assertDatabaseHas('users', [
        'name' => 'Petugas Baru',
        'email' => 'petugasbaru@test.com',
        'role' => 'petugas',
    ]);
});

test('admin can create admin from user form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)
        ->post(route('admin.register.post'), [
            'name' => 'Admin Baru',
            'email' => 'adminbaru@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'role' => 'admin',
        ]);

    $response->assertRedirectToRoute('admin.users.index');
    $this->assertDatabaseHas('users', [
        'name' => 'Admin Baru',
        'email' => 'adminbaru@test.com',
        'role' => 'admin',
    ]);
});

