<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalImunisasi;
use App\Models\LaporanImunisasi;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use App\Mail\ImunisasiNotification;
use App\Models\Balita;

class JadwalImunisasiController extends Controller
{
    public function index(): View
    {
        $jadwal_imunisasi = JadwalImunisasi::with('balita.user')->get();
        return view('admin.jadwal-imunisasi.index', compact('jadwal_imunisasi'));
    }

    public function create(): View
    {
        $balitas = Balita::all();
        return view('admin.jadwal-imunisasi.create', compact('balitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_vaksin' => 'required|string|max:255',
            'tanggal_imunisasi' => 'required|date',
        ]);

        JadwalImunisasi::create($request->only(['balita_id', 'jenis_vaksin', 'tanggal_imunisasi']));

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil ditambahkan.');
    }

    public function show($id)
    {
        return Redirect::route('admin.jadwal-imunisasi.edit', $id);
    }

    public function edit($id): View
    {
        $jadwal_imunisasi = JadwalImunisasi::findOrFail($id);
        $balitas = Balita::all();
        return view('admin.jadwal-imunisasi.edit', compact('jadwal_imunisasi', 'balitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_vaksin' => 'required|string|max:255',
            'tanggal_imunisasi' => 'required|date',
        ]);

        $jadwal = JadwalImunisasi::findOrFail($id);
        $jadwal->update($request->only(['balita_id', 'jenis_vaksin', 'tanggal_imunisasi']));

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil diubah.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalImunisasi::findOrFail($id);
        $jadwal->delete();

        return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil dihapus.');
    }

    public function selesai($id)
    {
        try {
            $jadwal = JadwalImunisasi::findOrFail($id);

            $laporan = LaporanImunisasi::firstOrNew([
                'jadwal_imunisasi_id' => $id,
            ]);

            $laporan->balita_id = $jadwal->balita_id;
            $laporan->jenis_vaksin = $jadwal->jenis_vaksin;
            $laporan->tanggal_imunisasi = $jadwal->tanggal_imunisasi;
            $laporan->status = 'selesai';
            $laporan->save();

            return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi diselesaikan dan disimpan di laporan.');

        } catch (ModelNotFoundException $e) {
            return Redirect::route('admin.jadwal-imunisasi.index')->with('error', 'Jadwal imunisasi tidak ditemukan.');
        }
    }

    public function panggil(Request $request, JadwalImunisasi $jadwal_imunisasi)
    {
        $request->validate([
            'nama_balita' => 'required|string',
            'jenis_vaksin' => 'required|string',
            'tanggal_imunisasi' => 'required|string',
            'user_email' => 'required|email',
        ]);

        try {
            Mail::to($request->user_email)->send(new ImunisasiNotification(
                $request->nama_balita,
                $request->jenis_vaksin,
                $request->tanggal_imunisasi
            ));

            \Log::info('Email notifikasi imunisasi berhasil dikirim.', [
                'email' => $request->user_email,
                'jadwal_id' => $jadwal_imunisasi->id
            ]);

            return response()->json(['message' => 'Notifikasi email berhasil dikirim!'], 200);

        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email notifikasi imunisasi: ' . $e->getMessage(), [
                'email' => $request->user_email,
                'error' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Gagal mengirim notifikasi email.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
