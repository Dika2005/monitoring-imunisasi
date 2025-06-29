<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;         // Import model Balita
use App\Models\JadwalImunisasi; // Import model JadwalImunisasi
use App\Models\RiwayatImunisasi; // Import model RiwayatImunisasi
use App\Models\User;           // Import model User jika ingin menghitung total user juga

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total balita
        $totalBalita = Balita::count();

        // Hitung total jadwal imunisasi
        $totalJadwal = JadwalImunisasi::count();

        // Hitung total catatan/laporan imunisasi (asumsi RiwayatImunisasi adalah "Catatan Record Balita")
        $totalRecord = RiwayatImunisasi::count();

        // Anda bisa juga menghitung total user jika perlu
        $totalUser = User::count();

        return view('admin.dashboard', compact('totalBalita', 'totalJadwal', 'totalRecord', 'totalUser'));
    }
}