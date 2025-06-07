<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\JadwalImunisasi;
use App\Models\Balita;
use Illuminate\Support\Facades\Auth;

class JadwalImunisasiController extends Controller
{
    /**
     * Tampilkan daftar sumber daya untuk user yang sudah terautentikasi.
     */
    public function index(): View
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat jadwal imunisasi.');
        }

        $user = Auth::user(); // Dapatkan user yang sedang login

        // Dapatkan semua ID balita yang terhubung dengan user ini
        // PERBAIKAN DI SINI: Gunakan 'user_id' BUKAN 'id_orang_tua'
        $balitaIds = Balita::where('user_id', $user->id)->pluck('id');

        // Pastikan $balitaIds bukan koleksi kosong sebelum digunakan di whereIn
        // agar tidak menghasilkan kueri SQL yang kosong atau tidak valid.
        $jadwal_imunisasi_user = collect(); // Inisialisasi sebagai koleksi kosong

        if ($balitaIds->isNotEmpty()) { // Gunakan isNotEmpty() untuk koleksi
            // Dapatkan jadwal imunisasi yang terkait dengan balita-balita user ini
            // Eager load relasi 'balita' untuk menampilkan nama balita
            $jadwal_imunisasi_user = JadwalImunisasi::with('balita')
                                     ->whereIn('balita_id', $balitaIds)
                                     ->orderBy('tanggal_imunisasi', 'asc') // Urutkan berdasarkan tanggal
                                     ->get();
        }

        return view('user.jadwal-imunisasi.index', compact('jadwal_imunisasi_user'));
    }
}