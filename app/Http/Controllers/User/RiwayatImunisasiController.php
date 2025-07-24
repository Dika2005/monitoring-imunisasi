<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RiwayatImunisasi;
use Illuminate\Support\Facades\Auth;

class RiwayatImunisasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $riwayats = RiwayatImunisasi::whereHas('balita', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('tanggal_imunisasi', 'desc')->get();

        return view('user.riwayat-imunisasi.index', compact('riwayats'));
    }
}
