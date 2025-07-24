<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrangTuaController;
use App\Http\Controllers\Admin\BalitaController;
use App\Http\Controllers\Admin\JadwalImunisasiController as AdminJadwalController;
use App\Http\Controllers\Admin\RiwayatImunisasiController as AdminRiwayatController;
use App\Http\Controllers\Admin\KetersediaanVaksinController;

// User Controllers
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\JadwalImunisasiController as UserJadwalController;
use App\Http\Controllers\User\RiwayatImunisasiController as UserRiwayatController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ✅ Gunakan alias AdminDashboardController
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

        Route::resource('orangtua', OrangTuaController::class);
        Route::resource('balita', BalitaController::class)->parameters([
    'balita' => 'balita'
]);

        Route::resource('jadwal-imunisasi', AdminJadwalController::class);

        Route::put('jadwal-imunisasi/{jadwalImunisasi}/selesai', [AdminJadwalController::class, 'selesai'])
            ->name('jadwal-imunisasi.selesai');

        Route::get('jadwal-imunisasi/{jadwalImunisasi}/panggil', [AdminJadwalController::class, 'panggil'])
            ->name('jadwal-imunisasi.panggil');

        Route::post('jadwal-imunisasi/{jadwalImunisasi}/panggil-wa', [AdminJadwalController::class, 'panggilWhatsapp'])
            ->name('jadwal-imunisasi.panggil-wa');

        // ✅ Riwayat Imunisasi (pakai alias)
        Route::resource('riwayat-imunisasi', AdminRiwayatController::class);

        Route::resource('ketersediaan-vaksin', KetersediaanVaksinController::class);
    });

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
        Route::resource('jadwal-imunisasi', UserJadwalController::class)->only(['index', 'show']);
        Route::get('riwayat-imunisasi', [UserRiwayatController::class, 'index'])->name('riwayat-imunisasi.index');
    });
