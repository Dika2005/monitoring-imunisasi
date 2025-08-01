<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatImunisasi;

class RiwayatImunisasiController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $orangtua = $user->orangtua;

    if (!$orangtua) {
        return redirect()->back()->with('error', 'Data orang tua tidak ditemukan.');
    }

    $riwayats = RiwayatImunisasi::whereHas('balita', function ($query) use ($orangtua) {
        $query->where('orangtua_id', $orangtua->id);
    })
    ->where(function ($query) {
        $query->where('status', 'selesai')
              ->orWhere('status', 'like', 'terlambat%');
    })
    ->with('balita.orangtua')
    ->latest()
    ->get();

    return view('user.riwayat-imunisasi.index', compact('riwayats'));
}
}

