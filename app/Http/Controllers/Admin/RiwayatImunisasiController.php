<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatImunisasi;
use Carbon\Carbon;

class RiwayatImunisasiController extends Controller
{
    public function index()
    {
        // Ambil data dengan relasi balita dan user
        $riwayat_imunisasi = RiwayatImunisasi::with('balita.user')->get();

        // Hitung umur untuk setiap balita
        foreach ($riwayat_imunisasi as $riwayat) {
            $balita = $riwayat->balita;

            if ($balita && $balita->tanggal_lahir) {
                $lahir = Carbon::parse($balita->tanggal_lahir);
                $sekarang = Carbon::now();

                $tahun = $lahir->diffInYears($sekarang);
                $bulan = $lahir->diffInMonths($sekarang) % 12;
                $hari = $lahir->diffInDays($sekarang->copy()->subYears($tahun)->subMonths($bulan));

                $umur = '';
                if ($tahun > 0) {
                    $umur .= "$tahun tahun ";
                }
                if ($bulan > 0) {
                    $umur .= "$bulan bulan ";
                }
                if ($tahun === 0 && $bulan === 0 && $hari > 0) {
                    $umur .= "$hari hari";
                }
                if (trim($umur) === '') {
                    $umur = 'Baru lahir';
                }

                $balita->umur_format = trim($umur);
            } else {
                $balita->umur_format = '-';
            }
        }

        return view('admin.riwayat.index', compact('riwayat_imunisasi'));
    }

    public function create()
    {
        return view('admin.riwayat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisasi,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        RiwayatImunisasi::create($request->all());

        return redirect()->route('admin.riwayat.index')->with('success', 'Riwayat imunisasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $riwayat = RiwayatImunisasi::findOrFail($id);
        return view('admin.riwayat.edit', compact('riwayat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisasi,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $riwayat = RiwayatImunisasi::findOrFail($id);
        $riwayat->update($request->all());

        return redirect()->route('admin.riwayat.index')->with('success', 'Riwayat imunisasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $riwayat = RiwayatImunisasi::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('admin.riwayat.index')->with('success', 'Riwayat imunisasi berhasil dihapus');
    }
}
