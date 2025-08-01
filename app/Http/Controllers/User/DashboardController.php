<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalImunisasi;
use App\Models\RiwayatImunisasi;
use App\Models\Balita;
use Carbon\Carbon;


class DashboardController extends Controller
{
   public function index()
{
    // Ambil jumlah imunisasi yang statusnya 'selesai'
    $jumlahSelesai = RiwayatImunisasi::where('status', 'selesai')->count();

    // Ambil jumlah imunisasi yang statusnya 'belum imunisasi'
    $jumlahBelum = RiwayatImunisasi::where('status', 'belum imunisasi')->count();

    // Ambil jadwal terdekat dari tabel jadwal_imunisasi (tanpa status)
    $jadwalTerdekat = JadwalImunisasi::whereDate('tanggal_imunisasi', '>=', Carbon::today())
                        ->orderBy('tanggal_imunisasi', 'asc')
                        ->get();

    return view('user.dashboard', compact('jumlahSelesai', 'jumlahBelum', 'jadwalTerdekat'));
}
}



    

