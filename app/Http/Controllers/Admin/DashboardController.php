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
    // Total
    $totalBalita = Balita::count();
    $totalJadwal = JadwalImunisasi::count();
    $totalRiwayat = RiwayatImunisasi::count();

     $riwayat = RiwayatImunisasi::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    // Imunisasi Per Bulan
    $bulanLabels = [];
    $jumlahPerBulan = [];

     for ($i = 1; $i <= 12; $i++) {
        $bulanLabels[] = Carbon::create()->month($i)->format('F'); // Nama bulan
        $data = $riwayat->firstWhere('bulan', $i);
        $jumlahPerBulan[] = $data ? $data->jumlah : 0;
    }

    // Status
    $statusCounts = [
        'selesai' => RiwayatImunisasi::where('status', 'selesai')->count(),
        'belum imunisasi' => RiwayatImunisasi::where('status', 'belum imunisasi')->count(),
        'terlambat' => RiwayatImunisasi::where('status', 'like', 'terlambat%')->count(),
    ];

    return view('admin.dashboard', compact(
        'totalBalita',
        'totalJadwal',
        'totalRiwayat',
        'bulanLabels',
        'jumlahPerBulan',
        'statusCounts'
    ));
}

}
