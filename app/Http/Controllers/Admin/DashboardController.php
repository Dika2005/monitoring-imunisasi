<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\RiwayatImunisasi;
use App\Models\Balita;
use App\Models\JadwalImunisasi;


class DashboardController extends Controller
{
   public function dashboard()
{
    // Bulan dan tahun sekarang
    $bulanIni = Carbon::now()->month;
    $tahunIni = Carbon::now()->year;

    // Total
    $totalBalita = Balita::count();
    $totalJadwalBulanIni = JadwalImunisasi::whereMonth('tanggal_imunisasi', $bulanIni)
                                ->whereYear('tanggal_imunisasi', $tahunIni)
                                ->count();
    $totalRiwayat = RiwayatImunisasi::count();

    // Riwayat per Bulan (untuk grafik/chart)
    $riwayat = RiwayatImunisasi::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $bulanLabels = [];
    $jumlahPerBulan = [];

    for ($i = 1; $i <= 12; $i++) {
        $bulanLabels[] = Carbon::create()->month($i)->format('F');
        $data = $riwayat->firstWhere('bulan', $i);
        $jumlahPerBulan[] = $data ? $data->jumlah : 0;
    }

    // Status Imunisasi
    $statusCounts = [
        'selesai' => RiwayatImunisasi::where('status', 'selesai')->count(),
        'belum imunisasi' => RiwayatImunisasi::where('status', 'belum imunisasi')->count(),
        'terlambat' => RiwayatImunisasi::where('status', 'like', 'terlambat%')->count(),
    ];

    return view('admin.dashboard', compact(
        'totalBalita',
        'totalJadwalBulanIni',
        'totalRiwayat',
        'bulanLabels',
        'jumlahPerBulan',
        'statusCounts'
    ));
}


}
