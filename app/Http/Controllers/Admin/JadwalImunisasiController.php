<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalImunisasi;
use App\Models\RiwayatImunisasi;
use App\Models\Balita;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;
use App\Services\WablasServices;
use Carbon\Carbon;

class JadwalImunisasiController extends Controller
{
    public function index(): View
    {
        $jadwal_imunisasi = JadwalImunisasi::with('balita.user')->get();

        foreach ($jadwal_imunisasi as $jadwal) {
            $balita = $jadwal->balita;

            if ($balita && $balita->tanggal_lahir) {
                $lahir = Carbon::parse($balita->tanggal_lahir);
                $sekarang = Carbon::now();

                $tahun = $lahir->diffInYears($sekarang);
                $bulan = $lahir->diffInMonths($sekarang) % 12;
                $hari = $lahir->diffInDays($sekarang->copy()->subYears($tahun)->subMonths($bulan));

                $umur = '';
                if ($tahun > 0) $umur .= "$tahun tahun ";
                if ($bulan > 0) $umur .= "$bulan bulan ";
                if ($tahun === 0 && $bulan === 0 && $hari > 0) $umur .= "$hari hari";
                if (trim($umur) === '') $umur = 'Baru lahir';

                $balita->umur_format = trim($umur);
            } else {
                $balita->umur_format = '-';
            }
        }

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
            $jadwal = JadwalImunisasi::with('balita')->findOrFail($id);

            $riwayat = RiwayatImunisasi::firstOrNew([
                'jadwal_imunisasi_id' => $id,
            ]);

            $riwayat->balita_id = $jadwal->balita_id;
            $riwayat->jenis_vaksin = $jadwal->jenis_vaksin;
            $riwayat->tanggal_imunisasi = now();

            $tanggal_jadwal = Carbon::parse($jadwal->tanggal_imunisasi);
            $tanggal_sekarang = Carbon::now();

            $riwayat->status = $tanggal_sekarang->gt($tanggal_jadwal) ? 'terlambat' : 'selesai';

            $riwayat->save();

            return Redirect::route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi diselesaikan dan disimpan ke riwayat.');
        } catch (ModelNotFoundException $e) {
            return Redirect::route('admin.jadwal-imunisasi.index')->with('error', 'Jadwal imunisasi tidak ditemukan.');
        }
    }

    public function panggilWhatsapp($id)
    {
        $jadwal = JadwalImunisasi::with('balita.user')->findOrFail($id);

        $noTelepon = $jadwal->balita->no_telepon;
        $namaBalita = $jadwal->balita->nama;
        $jenisVaksin = $jadwal->jenis_vaksin;
        $tanggalImunisasi = Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y');

        $pesan = "Halo, jadwal imunisasi untuk *$namaBalita* dengan vaksin *$jenisVaksin* dijadwalkan pada *$tanggalImunisasi*. Mohon hadir tepat waktu.";

        try {
            $wablas = new WablasServices();
            $wablas->kirimPesan($noTelepon, $pesan);

            return response()->json(['message' => 'Notifikasi WhatsApp berhasil dikirim!'], 200);
        } catch (\Exception $e) {
            \Log::error('Gagal kirim WA: ' . $e->getMessage());

            return response()->json([
                'message' => 'Gagal mengirim notifikasi WhatsApp.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
