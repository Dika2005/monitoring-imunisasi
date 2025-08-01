<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JadwalImunisasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalImunisasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orangtua = $user->orangtua;

        if (!$orangtua) {
            return redirect()->back()->with('error', 'Data orang tua tidak ditemukan.');
        }

        // Ambil jadwal imunisasi yang belum dipindahkan ke riwayat
        $jadwals = JadwalImunisasi::whereHas('balita', function ($query) use ($orangtua) {
                $query->where('orangtua_id', $orangtua->id);
            })
            ->with('balita')
            ->orderBy('tanggal_imunisasi', 'asc')
            ->get()
            ->map(function ($jadwal) {
                $today = Carbon::today();
                $tanggalImunisasi = Carbon::parse($jadwal->tanggal_imunisasi);

                // Cek apakah sudah lewat dari tanggal
                if ($today->gt($tanggalImunisasi)) {
                    $selisihHari = $today->diffInDays($tanggalImunisasi);
                    $jadwal->status = 'Belum Imunisasi (Terlambat ' . $selisihHari . ' hari)';
                } else {
                    $jadwal->status = 'Belum Imunisasi';
                }

                return $jadwal;
            });

        return view('user.jadwal-imunisasi.index', [
            'jadwals' => $jadwals
        ]);
    }
}
