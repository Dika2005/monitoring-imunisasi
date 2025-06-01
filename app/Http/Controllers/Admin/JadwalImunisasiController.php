<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalImunisasi;
use App\Models\LaporanImunisasi; // Import model LaporanImunisasi
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JadwalImunisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Eager load relasi 'balita' untuk menghindari N+1 query problem
        $jadwal_imunisasi = JadwalImunisasi::with('balita')->get();
        return view('admin.jadwal-imunisasi.index', compact('jadwal_imunisasi')); // Perbaikan nama view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $balitas = \App\Models\Balita::all(); // Ambil data semua balita untuk dropdown
        return view('admin.jadwal-imunisasi.create', compact('balitas')); // Perbaikan nama view
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_vaksin' => 'required|string|max:255',
            'tanggal_imunisasi' => 'required|date',
        ]);

        $jadwal = new JadwalImunisasi();
        $jadwal->balita_id = $request->balita_id;
        $jadwal->jenis_vaksin = $request->jenis_vaksin;
        $jadwal->tanggal_imunisasi = $request->tanggal_imunisasi;
        $jadwal->save();

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Tidak diperlukan view khusus untuk show, redirect ke edit atau index
        $jadwal = JadwalImunisasi::findOrFail($id);
        return Redirect::route('admin.jadwal-imunisasi.edit', $jadwal->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $jadwal_imunisasi = JadwalImunisasi::findOrFail($id);
        $balitas = \App\Models\Balita::all(); // Ambil data semua balita untuk dropdown
        return view('admin.jadwal-imunisasi.edit', compact('jadwal_imunisasi', 'balitas')); // Perbaikan nama view
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_vaksin' => 'required|string|max:255',
            'tanggal_imunisasi' => 'required|date',
        ]);

        $jadwal_imunisasi = JadwalImunisasi::findOrFail($id);
        $jadwal_imunisasi->balita_id = $request->balita_id;
        $jadwal_imunisasi->jenis_vaksin = $request->jenis_vaksin;
        $jadwal_imunisasi->tanggal_imunisasi = $request->tanggal_imunisasi;
        $jadwal_imunisasi->save();

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jadwal_imunisasi = JadwalImunisasi::findOrFail($id);
        $jadwal_imunisasi->delete();

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil dihapus.');
    }

    public function selesai($id)
    {
        try {
            $jadwal_imunisasi = JadwalImunisasi::findOrFail($id);

            // Cek apakah data laporan imunisasi sudah ada
            $laporan_imunisasi = LaporanImunisasi::where('jadwal_imunisasi_id', $id)->first();

            if (!$laporan_imunisasi) {
                // Buat entri baru di tabel laporan_imunisasi
                $laporan_imunisasi = new LaporanImunisasi();
                $laporan_imunisasi->jadwal_imunisasi_id = $id;
                $laporan_imunisasi->balita_id = $jadwal_imunisasi->balita_id; //ambil dari jadwal
                $laporan_imunisasi->jenis_vaksin = $jadwal_imunisasi->jenis_vaksin; //ambil dari jadwal
                $laporan_imunisasi->tanggal_imunisasi = $jadwal_imunisasi->tanggal_imunisasi; //ambil dari jadwal
                $laporan_imunisasi->status = 'selesai'; // Set status selesai
                $laporan_imunisasi->save();
            } else {
                $laporan_imunisasi->status = 'selesai';
                $laporan_imunisasi->save();
            }

            // Redirect ke halaman index dengan pesan sukses
            return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil diselesaikan dan disimpan di laporan.');
        } catch (ModelNotFoundException $e) {
            // Handle jika jadwal imunisasi tidak ditemukan
            return Redirect::route('admin.jadwal-imunisasi.index')->with('error', 'Jadwal imunisasi tidak ditemukan.');
        }
    }
}

