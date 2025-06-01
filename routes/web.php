<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BalitaController;
use App\Http\Controllers\Admin\JadwalImunisasiController;
use App\Http\Controllers\Admin\LaporanImunisasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Middleware\CheckRole;

// Halaman login dan register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', CheckRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Data Balita Routes
    Route::resource('balita', BalitaController::class);

    // Jadwal Imunisasi Routes
    Route::resource('jadwal-imunisasi', JadwalImunisasiController::class);
    Route::post('jadwal-imunisasi/{id}/selesai', [JadwalImunisasiController::class, 'selesai'])->name('jadwal-imunisasi.selesai');

    // Laporan Routes
    Route::get('laporan', [LaporanImunisasiController::class, 'index'])->name('laporan.index');
});

// User routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Dashboard User
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
});
