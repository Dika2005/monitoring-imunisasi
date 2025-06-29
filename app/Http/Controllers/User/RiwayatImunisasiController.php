<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiwayatImunisasi;
use App\Models\Balita;
use Illuminate\Support\Facades\Auth;

class RiwayatImunisasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pastikan user sudah login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat riwayat imunisasi.');
        }

        // 1. Dapatkan semua ID balita yang dimiliki oleh user yang sedang login
        $balitaIds = $user->balitas->pluck('id')->toArray();
        // Alternatif jika relasi belum ada di model User:
        // $balitaIds = Balita::where('user_id', $user->id)->pluck('id')->toArray();

        $riwayats = collect(); // Inisialisasi koleksi kosong

        // 2. Ambil data riwayat imunisasi jika user punya balita
        if (!empty($balitaIds)) {
            $riwayats = RiwayatImunisasi::with('balita') // Eager load relasi balita jika dibutuhkan
                                        ->whereIn('balita_id', $balitaIds)
                                        ->orderBy('tanggal_imunisasi', 'desc')
                                        ->get();
        }

        return view('user.riwayat.index', compact('riwayats'));
    }
}
