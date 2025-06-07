<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanImunisasi;
use App\Models\Balita; // Pastikan ini diimport
use Illuminate\Support\Facades\Auth;

class LaporanImunisasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Pastikan user sudah login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat laporan imunisasi.');
        }

        // 1. Dapatkan semua ID balita yang dimiliki oleh user yang sedang login
        // Asumsikan relasi 'balitas' sudah didefinisikan di App\Models\User.php
        $balitaIds = $user->balitas->pluck('id')->toArray();
        // Alternatif jika relasi di User.php belum ada:
        // $balitaIds = Balita::where('user_id', $user->id)->pluck('id')->toArray();

        $laporans = collect(); // Inisialisasi sebagai koleksi kosong

        // 2. Jika user memiliki balita, ambil laporan imunisasi untuk balita-balita tersebut
        if (!empty($balitaIds)) {
            $laporans = LaporanImunisasi::with('balita') // Eager load relasi balita jika Anda membutuhkannya di view
                                        ->whereIn('balita_id', $balitaIds)
                                        ->orderBy('tanggal_imunisasi', 'desc')
                                        ->get();
        }

        return view('user.laporan.index', compact('laporans'));
    }
}