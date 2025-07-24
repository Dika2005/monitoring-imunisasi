<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatImunisasi;
use Carbon\Carbon;

class RiwayatImunisasiController extends Controller
{
    public function index(Request $request)
{
    $query = RiwayatImunisasi::with('balita.orangtua');

    if ($request->filled('search')) {
        $query->whereHas('balita', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('vaksin')) {
        $query->where('jenis_vaksin', $request->vaksin);
    }

    if ($request->filled('status')) {
    if ($request->status === 'terlambat') {
        $query->where('status', 'like', 'terlambat%');
    } else {
        $query->where('status', $request->status);
    }
}


    $riwayatImunisasi = $query->latest()->get();

    foreach ($riwayatImunisasi as $riwayat) {
        $tanggalLahir = $riwayat->balita->tanggal_lahir;
        $umur = Carbon::parse($tanggalLahir)->diff(Carbon::now());

        $riwayat->umur_format = 
            ($umur->y ? $umur->y . ' tahun ' : '') .
            ($umur->m ? $umur->m . ' bulan ' : '') .
            ($umur->d ? $umur->d . ' hari' : '');

        // Hitung tampilan status keterlambatan
        if ($riwayat->status === 'terlambat') {
            $selisih = Carbon::parse($riwayat->tanggal_imunisasi)->diffInDays(Carbon::parse($riwayat->created_at));
            $riwayat->status_format = 'terlambat ' . $selisih . ' hari';
        } else {
            $riwayat->status_format = 'selesai';
        }
    }

    $daftar_jenis_vaksin = RiwayatImunisasi::select('jenis_vaksin')->distinct()->pluck('jenis_vaksin');

    return view('admin.riwayat-imunisasi.index', [
        'riwayat_imunisasi' => $riwayatImunisasi,
        'daftar_jenis_vaksin' => $daftar_jenis_vaksin,
    ]);
}

}
