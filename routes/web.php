<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BalitaController;
use App\Http\Controllers\Admin\JadwalImunisasiController;
use App\Http\Controllers\Admin\RiwayatImunisasiController as AdminRiwayatImunisasiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\JadwalImunisasiController as UserJadwalImunisasiController;
use App\Http\Controllers\User\RiwayatImunisasiController as UserRiwayatImunisasiController;
use App\Http\Middleware\CheckRole;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', CheckRole::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('balita', BalitaController::class)->parameters([
            'balita' => 'balita'
        ]);

        Route::resource('jadwal-imunisasi', JadwalImunisasiController::class)->parameters([
            'jadwal-imunisasi' => 'jadwalImunisasi'
        ]);

        Route::put('jadwal-imunisasi/{jadwalImunisasi}/selesai', [JadwalImunisasiController::class, 'selesai'])
            ->name('jadwal-imunisasi.selesai');

        Route::post('jadwal-imunisasi/{jadwalImunisasi}/panggil', [JadwalImunisasiController::class, 'panggil'])
            ->name('jadwal-imunisasi.panggil');

        Route::post('jadwal-imunisasi/{id}/panggil-wa', [JadwalImunisasiController::class, 'panggilWhatsapp'])
            ->name('jadwal-imunisasi.panggil-wa');

        Route::get('riwayat', [AdminRiwayatImunisasiController::class, 'index'])->name('riwayat.index');
    });

/*
|--------------------------------------------------------------------------
| User Routes (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', CheckRole::class . ':user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        Route::get('/dashboard/jadwal-imunisasi', [UserJadwalImunisasiController::class, 'index'])
            ->name('jadwal-imunisasi.index');

        Route::get('/riwayat', [UserRiwayatImunisasiController::class, 'index'])->name('riwayat.index');
    });
