<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Login redirect routes
Route::get('/login', function () {
    return redirect()->route('petugas.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', \App\Http\Middleware\Admin::class])->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User management (includes petugas)
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
    });
});

Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\PetugasAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\PetugasAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [\App\Http\Controllers\Auth\PetugasAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', \App\Http\Middleware\Petugas::class])->group(function () {
        Route::get('/', function () {
            return redirect()->route('petugas.dashboard');
        })->name('home');

        Route::get('dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');
    });
});
