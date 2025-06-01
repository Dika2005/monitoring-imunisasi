<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\JadwalImunisasi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua balita milik user
        $balitaIds = Balita::where('user_id', $userId)->pluck('id');

        // Hitung status imunisasi dari jadwal balita
        $sudah = JadwalImunisasi::whereIn('balita_id', $balitaIds)
                    ->where('status', 'selesai')
                    ->count();

        $akanDatang = JadwalImunisasi::whereIn('balita_id', $balitaIds)
                        ->where('tanggal', '>', now())
                        ->where('status', 'belum')
                        ->count();

        $belum = JadwalImunisasi::whereIn('balita_id', $balitaIds)
                    ->where('status', 'belum')
                    ->where('tanggal', '<=', now())
                    ->count();

        return view('user.dashboard.index', compact('sudah', 'akanDatang', 'belum'));
    }
}
