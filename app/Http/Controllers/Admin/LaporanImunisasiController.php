<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanImunisasi;

class LaporanImunisasiController extends Controller
{
    public function index()
{
    $laporan_imunisasi = LaporanImunisasi::all(); // ambil data dari model

    return view('admin.laporan.index', compact('laporan_imunisasi'));
}

    public function create()
    {
        return view('admin.laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisis,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        LaporanImunisasi::create($request->all());

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan imunisasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $laporan = LaporanImunisasi::findOrFail($id);
        return view('admin.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisis,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|max:255',
        ]);

        $laporan = LaporanImunisasi::findOrFail($id);
        $laporan->update($request->all());

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan imunisasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $laporan = LaporanImunisasi::findOrFail($id);
        $laporan->delete();

        return redirect()->route('admin.laporan.index')->with('success', 'Laporan imunisasi berhasil dihapus');
    }
}
