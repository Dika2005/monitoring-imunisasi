<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalImunisasi;
use App\Models\RiwayatImunisasi;
use App\Models\Balita; // <<< Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Dapatkan semua ID balita yang dimiliki oleh user ini
        // Asumsikan user memiliki relasi hasMany ke Balita di model User.php
        // atau kita bisa query langsung ke model Balita
        $balitaIds = $user->balitas()->pluck('id')->toArray();
        // Alternatif jika relasi di User.php belum ada:
        // $balitaIds = Balita::where('user_id', $user->id)->pluck('id')->toArray();


        $jadwal = collect(); // Inisialisasi koleksi kosong
        $riwayat = collect(); // Inisialisasi koleksi kosong

        // 2. Hanya ambil jadwal dan riwayat jika user memiliki balita
        if (!empty($balitaIds)) {
            // Ambil jadwal imunisasi untuk balita-balita ini
            $jadwal = JadwalImunisasi::whereIn('balita_id', $balitaIds)->get();

            // Ambil riwayat imunisasi untuk balita-balita ini
            $riwayat = RiwayatImunisasi::whereIn('balita_id', $balitaIds)->get();
        }

        return view('user.dashboard', compact('user', 'jadwal', 'riwayat'));
    }
}