<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JadwalImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalImunisasiController extends Controller
{
    public function index()
    {
        // Ambil jadwal imunisasi milik balita user yg sedang login
        // Asumsi: relasi user -> balita sudah ada dan 1 user bisa punya banyak balita
        $user = Auth::user();
        $jadwals = JadwalImunisasi::whereHas('balita', function ($query) use ($user) {
            $query->where('user_id', $user->id); // sesuaikan nama kolom user_id di balita
        })->orderBy('tanggal_imunisasi', 'asc')->get();

        return view('user.jadwal-imunisasi.index', compact('jadwals'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $jadwal = JadwalImunisasi::where('id', $id)->whereHas('balita', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->firstOrFail();

        return view('user.jadwal-imunisasi.show', compact('jadwal'));
    }
}
