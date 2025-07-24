<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalImunisasi;
use App\Models\Balita;
use App\Models\KetersediaanVaksin;
use App\Models\RiwayatImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\WablasServices;
use Carbon\Carbon;

class JadwalImunisasiController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalImunisasi::with(['balita.orangtua', 'riwayat']);

        if ($request->filled('nama')) {
            $query->whereHas('balita', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        if ($request->filled('jenis_vaksin')) {
            $query->where('jenis_vaksin', $request->jenis_vaksin);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_imunisasi', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_imunisasi', $request->tahun);
        }

        $query->where(function ($query) {
            $query->whereDoesntHave('riwayat')
                ->orWhereHas('riwayat', function ($subQuery) {
                    $subQuery->where('status', 'like', '%belum imunisasi%');
                });
        });

        $jadwal_imunisasi = $query->latest()->get();
        $semua_vaksin = JadwalImunisasi::select('jenis_vaksin')->distinct()->pluck('jenis_vaksin');

        $jadwal_imunisasi->map(function ($jadwal) {
            $riwayat = $jadwal->riwayat;
            $tanggalJadwal = Carbon::parse($jadwal->tanggal_imunisasi);
            $sekarang = Carbon::now();

            if (!$riwayat || str_contains($riwayat->status, 'belum imunisasi')) {
                if ($sekarang->greaterThan($tanggalJadwal)) {
                    $hariTerlambat = $sekarang->diffInDays($tanggalJadwal);
                    $jadwal->status_display = "terlambat {$hariTerlambat} hari";
                } else {
                    $jadwal->status_display = "menunggu";
                }
            } else {
                $jadwal->status_display = $riwayat->status;
            }

            return $jadwal;
        });

        return view('admin.jadwal-imunisasi.index', compact('jadwal_imunisasi', 'semua_vaksin'));
    }

    public function create()
    {
        $balitas = Balita::all();
        $vaksins = KetersediaanVaksin::where('stok', '>', 0)->get();

        return view('admin.jadwal-imunisasi.create', compact('balitas', 'vaksins'));
    }

    public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'balita_id' => 'required|exists:balitas,id',
            'jenis_vaksin' => 'required|string',
            'tanggal_imunisasi' => 'required|date',
        ]);

        // Kurangi stok vaksin
        $vaksin = KetersediaanVaksin::where('nama_vaksin', $request->jenis_vaksin)->first();

        if (!$vaksin) {
            return redirect()->back()->with('error', 'Jenis vaksin tidak ditemukan.');
        }

        if ($vaksin->stok < 1) {
            return redirect()->back()->with('error', 'Stok vaksin tidak mencukupi.');
        }

        $vaksin->stok -= 1;
        $vaksin->save();

        // Simpan jadwal imunisasi
        JadwalImunisasi::create([
            'balita_id' => $request->balita_id,
            'jenis_vaksin' => $request->jenis_vaksin,
            'tanggal_imunisasi' => $request->tanggal_imunisasi,
        ]);

        DB::commit();
        return redirect()->route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal berhasil ditambahkan dan stok vaksin berkurang.');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Gagal menambahkan jadwal imunisasi: " . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    }
}

    public function edit($id)
{
    $jadwalImunisasi = JadwalImunisasi::findOrFail($id); // Gunakan nama ini biar match dengan Blade
    $balitas = Balita::all();
    $vaksins = KetersediaanVaksin::where('stok', '>', 0)->get();

    return view('admin.jadwal-imunisasi.edit', compact('jadwalImunisasi', 'balitas', 'vaksins'));
}


    public function update(Request $request, $id)
{
    $request->validate([
        'balita_id' => 'required|exists:balitas,id',
        'jenis_vaksin' => 'required|string',
        'tanggal_imunisasi' => 'required|date',
    ]);

    $jadwal = JadwalImunisasi::findOrFail($id);

    // Cek jika jenis vaksin diganti
    if ($jadwal->jenis_vaksin !== $request->jenis_vaksin) {
        // Tambah stok vaksin lama
        KetersediaanVaksin::where('nama_vaksin', $jadwal->jenis_vaksin)->increment('stok');

        // Kurangi stok vaksin baru
        KetersediaanVaksin::where('nama_vaksin', $request->jenis_vaksin)->decrement('stok');
    }

    // Update data
    $jadwal->update([
        'balita_id' => $request->balita_id,
        'jenis_vaksin' => $request->jenis_vaksin,
        'tanggal_imunisasi' => $request->tanggal_imunisasi,
    ]);

    return redirect()->route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal berhasil diperbarui.');
}


    public function destroy($id)
    {
        $jadwal = JadwalImunisasi::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal-imunisasi.index')->with('success', 'Jadwal imunisasi berhasil dihapus.');
    }

   public function selesai($id)
{
    DB::beginTransaction();

    try {
        // Ambil data jadwal + relasi balita
        $jadwal = JadwalImunisasi::with('balita')->findOrFail($id);
        $balita = $jadwal->balita;

        // Hitung status keterlambatan
        $jadwalTanggal = \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->startOfDay();
        $hariIni = now()->startOfDay();
        $terlambat = $jadwalTanggal->diffInDays($hariIni, false);
        $status = $terlambat > 0 ? "terlambat {$terlambat} hari" : "selesai";

        // Cari data di riwayat berdasarkan jadwal dan balita
        $riwayat = RiwayatImunisasi::where('jadwal_imunisasi_id', $jadwal->id)
                    ->where('balita_id', $balita->id)
                    ->first();

        if ($riwayat) {
            // Jika sudah ada, update saja
            $riwayat->update([
                'tanggal_imunisasi' => now(),
                'status' => $status,
                'suhu_badan' => $balita->suhu_badan,
                'berat_badan' => $balita->berat_badan,
                'tinggi_badan' => $balita->tinggi_badan,
            ]);
        } else {
            // Jika tidak ada, bisa di-skip atau dibuat baru tergantung kebutuhan
            return redirect()->back()->with('error', 'Data riwayat belum ada. Harap tekan tombol "Panggil" terlebih dahulu.');
        }

        // Setelah update riwayat, hapus dari jadwal
        $jadwal->delete();

        DB::commit();
        return redirect()->back()->with('success', 'Status imunisasi berhasil diperbarui di riwayat.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Gagal update status ke riwayat: " . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memperbarui status imunisasi.');
    }
}




    public function panggil($id)
    {
        $jadwal = JadwalImunisasi::with('balita')->findOrFail($id);
        $balita = $jadwal->balita;

        $hariIni = Carbon::now();
        $jadwalTanggal = Carbon::parse($jadwal->tanggal_imunisasi);

        $hariTerlambat = $hariIni->greaterThan($jadwalTanggal)
            ? $hariIni->diffInDays($jadwalTanggal)
            : 0;

        $status = $hariTerlambat > 0
            ? "belum imunisasi (terlambat {$hariTerlambat} hari)"
            : "belum imunisasi";

        RiwayatImunisasi::updateOrCreate(
    ['jadwal_imunisasi_id' => $jadwal->id],
    [
        'balita_id' => $jadwal->balita_id,
        'jenis_vaksin' => $jadwal->jenis_vaksin,
        'tanggal_imunisasi' => now(),
        'status' => $status,
        'suhu_badan' => $balita->suhu_badan,
        'berat_badan' => $balita->berat_badan,
        'tinggi_badan' => $balita->tinggi_badan,
    ]
        );

        return redirect()->route('admin.jadwal-imunisasi.index')->with('success', 'Panggilan berhasil diproses dan data disimpan ke riwayat.');
    }
}
