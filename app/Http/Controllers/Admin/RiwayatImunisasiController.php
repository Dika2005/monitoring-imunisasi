<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatImunisasi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class RiwayatImunisasiController extends Controller
{
    public function index(Request $request)
{
    $query = RiwayatImunisasi::with('balita.orangtua');

    // Filter pencarian nama balita
    if ($request->filled('search')) {
        $query->whereHas('balita', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    // Filter berdasarkan vaksin
    if ($request->filled('vaksin')) {
        $query->where('jenis_vaksin', $request->vaksin);
    }

    // Filter berdasarkan status
    if ($request->filled('status')) {
        if ($request->status === 'terlambat') {
            $query->where('status', 'like', 'terlambat%');
        } else {
            $query->where('status', $request->status);
        }
    }

    $riwayatImunisasi = $query->latest()->get();

    foreach ($riwayatImunisasi as $riwayat) {
        // Hitung umur balita
        if ($riwayat->balita && $riwayat->balita->tanggal_lahir) {
            $tanggalLahir = Carbon::parse($riwayat->balita->tanggal_lahir);
            $umur = $tanggalLahir->diff(Carbon::now());

            $riwayat->umur_format =
                ($umur->y ? $umur->y . ' tahun ' : '') .
                ($umur->m ? $umur->m . ' bulan ' : '') .
                ($umur->d ? $umur->d . ' hari' : '');
        } else {
            $riwayat->umur_format = '-';
        }

        // Format status keterlambatan
        if (str_starts_with($riwayat->status, 'terlambat')) {
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
    public function cetakSurat($id)
{
    $riwayat = RiwayatImunisasi::with('balita.orangtua')->findOrFail($id);

    // Hitung umur balita
    if ($riwayat->balita && $riwayat->balita->tanggal_lahir) {
        $tanggalLahir = Carbon::parse($riwayat->balita->tanggal_lahir);
        $umur = $tanggalLahir->diff(Carbon::now());

        $riwayat->umur_format =
            ($umur->y ? $umur->y . ' tahun ' : '') .
            ($umur->m ? $umur->m . ' bulan ' : '') .
            ($umur->d ? $umur->d . ' hari' : '');
    } else {
        $riwayat->umur_format = '-';
    }

    // Format status keterlambatan
    if (str_starts_with($riwayat->status, 'terlambat')) {
        $selisih = Carbon::parse($riwayat->tanggal_imunisasi)->diffInDays(Carbon::parse($riwayat->created_at));
        $riwayat->status_format = 'terlambat ' . $selisih . ' hari';
    } else {
        $riwayat->status_format = 'selesai';
    }

    $pdf = PDF::loadView('admin.riwayat-imunisasi.surat', compact('riwayat'))
             ->setPaper('A4', 'portrait');

    return $pdf->download('surat-keterangan-imunisasi.pdf');
}

}
