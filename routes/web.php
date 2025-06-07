<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BalitaController;
use App\Http\Controllers\Admin\JadwalImunisasiController;
use App\Http\Controllers\Admin\LaporanImunisasiController as AdminLaporanImunisasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\JadwalImunisasiController as UserJadwalImunisasiController;
use App\Http\Controllers\User\LaporanImunisasiController as UserLaporanImunisasiController;
use App\Http\Middleware\CheckRole;

// Halaman login dan register
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes dengan middleware CheckRole:admin
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('balita', BalitaController::class);

        Route::resource('jadwal-imunisasi', JadwalImunisasiController::class);

        // Menandai jadwal sebagai selesai (method PUT)
        Route::put('jadwal-imunisasi/{jadwalImunisasi}/selesai', [JadwalImunisasiController::class, 'selesai'])->name('jadwal-imunisasi.selesai');

        // Kirim notifikasi "panggil"
        Route::post('jadwal-imunisasi/{jadwalImunisasi}/panggil', [JadwalImunisasiController::class, 'panggil'])->name('jadwal-imunisasi.panggil');

        // Laporan imunisasi admin
        Route::get('laporan', [AdminLaporanImunisasiController::class, 'index'])->name('laporan.index');
    });

// User routes dengan middleware CheckRole:user
Route::middleware(['auth', CheckRole::class . ':user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/dashboard/jadwal-imunisasi', [UserJadwalImunisasiController::class, 'index'])->name('jadwal-imunisasi.index');

        Route::get('/laporan', [UserLaporanImunisasiController::class, 'index'])->name('laporan.index');
    });
