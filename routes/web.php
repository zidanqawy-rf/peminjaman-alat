<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\PetugasAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ========================================
// PUBLIC ROUTES
// ========================================
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
    return view('welcome');
})->name('home');

// ========================================
// LOGIN ROUTES - UNIVERSAL LOGIN
// ========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// Logout route (untuk semua role)
Route::post('/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ========================================
// USER ROUTES (Dashboard User Biasa)
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        // Redirect jika ternyata admin atau petugas
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.dashboard');
        }
        
        // Tampilkan dashboard user biasa
        return view('dashboard');
    })->name('dashboard');

    // Profile routes (untuk semua user yang login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========================================
    // PEMINJAMAN ROUTES (UNTUK USER BIASA)
    // ========================================
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::get('/create', [PeminjamanController::class, 'create'])->name('create'); // ← UBAH dari '/buat' ke '/create'
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{peminjaman}', [PeminjamanController::class, 'show'])->name('show');
        
        // USER: Ajukan pengembalian (dengan foto & tanggal)
        Route::post('/{peminjaman}/ajukan-pengembalian', [PeminjamanController::class, 'ajukanPengembalian'])->name('ajukan-pengembalian');
        
        // USER: Upload bukti pembayaran denda
        Route::post('/{peminjaman}/upload-bukti-pembayaran', [PeminjamanController::class, 'uploadBuktiPembayaran'])->name('upload-bukti-pembayaran');
    });
    
    // Route untuk mencari alat (AJAX)
    Route::get('/alat/cari', [PeminjamanController::class, 'cariAlat'])->name('alat.cari');
});

// ========================================
// ADMIN ROUTES
// ========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Login admin (redirect ke universal login)
    Route::middleware('guest')->group(function () {
        Route::get('login', function () {
            return redirect()->route('login');
        })->name('login');
    });

    // Protected admin routes
    Route::middleware(['auth', \App\Http\Middleware\Admin::class])->group(function () {
        
        // Redirect admin home ke dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // ========================================
        // USER MANAGEMENT
        // ========================================
        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('register', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('register');
        Route::post('register', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('register.post');
        Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // User import
        Route::get('users/import/form', [\App\Http\Controllers\Admin\UserImportController::class, 'showImportForm'])->name('users.import.form');
        Route::post('users/import', [\App\Http\Controllers\Admin\UserImportController::class, 'import'])->name('users.import');
        Route::get('users/import/template', [\App\Http\Controllers\Admin\UserImportController::class, 'downloadTemplate'])->name('users.template');

        // ========================================
        // KATEGORI MANAGEMENT
        // ========================================
        Route::resource('kategori', KategoriController::class)->except(['show', 'create', 'edit']);

        // ========================================
        // ALAT MANAGEMENT
        // ========================================
        Route::get('alat/template/download', [AlatController::class, 'template'])->name('alat.template');
        Route::post('alat/import', [AlatController::class, 'import'])->name('alat.import');
        Route::resource('alat', AlatController::class);
    });
});

// ========================================
// PETUGAS ROUTES
// ========================================
Route::prefix('petugas')->name('petugas.')->group(function () {
    
    // Login petugas (redirect ke universal login)
    Route::middleware('guest')->group(function () {
        Route::get('login', function () {
            return redirect()->route('login');
        })->name('login');
    });

    // Protected petugas routes
    Route::middleware(['auth', \App\Http\Middleware\Petugas::class])->group(function () {
        
        // Redirect petugas home ke dashboard
        Route::get('/', function () {
            return redirect()->route('petugas.dashboard');
        })->name('home');

        // Dashboard
        Route::get('dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');

        // ========================================
        // KELOLA PEMINJAMAN
        // ========================================
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'index'])->name('index');
            Route::get('/{peminjaman}', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'show'])->name('show');
            
            // Update status peminjaman
            Route::patch('/{peminjaman}/approve', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'approve'])->name('approve');
            Route::patch('/{peminjaman}/reject', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'reject'])->name('reject');
            Route::patch('/{peminjaman}/serahkan', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'serahkan'])->name('serahkan');
            Route::patch('/{peminjaman}/terima-kembali', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'terimaKembali'])->name('terima-kembali');
            
            // PETUGAS: Verifikasi pembayaran denda
            Route::patch('/{peminjaman}/verifikasi-pembayaran', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'verifikasiPembayaran'])->name('verifikasi-pembayaran');
            Route::patch('/{peminjaman}/tolak-pembayaran', [\App\Http\Controllers\Petugas\PeminjamanController::class, 'tolakPembayaran'])->name('tolak-pembayaran');
        });

        // ========================================
        // LAPORAN
        // ========================================
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Petugas\LaporanController::class, 'index'])->name('index');
            Route::get('/print', [\App\Http\Controllers\Petugas\LaporanController::class, 'print'])->name('print');
            Route::get('/export', [\App\Http\Controllers\Petugas\LaporanController::class, 'export'])->name('export');
        });
    });
});

// ========================================
// AUTH ROUTES (dari Breeze/Fortify)
// ========================================
require __DIR__.'/auth.php';