<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Petugas Authentication', function () {
    test('petugas dapat login ke dashboard petugas', function () {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->post(route('petugas.login.post'), [
            'email' => $petugas->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('petugas.dashboard'));
        $this->assertAuthenticatedAs($petugas);
    });

    test('petugas tidak bisa login dengan password salah', function () {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->post(route('petugas.login.post'), [
            'email' => $petugas->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    });

    test('user biasa tidak bisa login ke petugas login', function () {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->post(route('petugas.login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('petugas.login'));
        $this->assertGuest();
    });

    test('admin tidak bisa login ke petugas login', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post(route('petugas.login.post'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('petugas.login'));
        $this->assertGuest();
    });

    test('petugas dapat logout', function () {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $this->actingAs($petugas);

        $response = $this->post(route('petugas.logout'));

        $response->assertRedirect(route('petugas.login'));
        $this->assertGuest();
    });
});

describe('Petugas Dashboard', function () {
    test('petugas yang sudah login dapat mengakses dashboard petugas', function () {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get(route('petugas.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Petugas');
    });

    test('petugas yang belum login tidak bisa mengakses dashboard', function () {
        $response = $this->get(route('petugas.dashboard'));

        $response->assertRedirect();
    });

    test('user biasa tidak bisa mengakses dashboard petugas', function () {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('petugas.dashboard'));

        $response->assertRedirect(route('petugas.login'));
    });

    test('admin tidak bisa mengakses dashboard petugas menggunakan middleware petugas', function () {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('petugas.dashboard'));

        $response->assertRedirect(route('petugas.login'));
    });
});
