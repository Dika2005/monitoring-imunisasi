<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalImunisasi;
use App\Models\Balita;
use App\Models\KetersediaanVaksin;
use App\Models\RiwayatImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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
            'jenis_imunisasi' => 'required|string',
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

        // Ambil data balita
        $balita = Balita::findOrFail($request->balita_id);

        // Hitung umur saat tanggal imunisasi
        $tanggalLahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
        $tanggalImunisasi = \Carbon\Carbon::parse($request->tanggal_imunisasi);
        $umur = $tanggalLahir->diff($tanggalImunisasi);

        $umurFormat = 
            ($umur->y ? $umur->y . ' tahun ' : '') .
            ($umur->m ? $umur->m . ' bulan ' : '') .
            ($umur->d ? $umur->d . ' hari' : '');

        // Simpan jadwal imunisasi
        JadwalImunisasi::create([
            'balita_id' => $request->balita_id,
            'jenis_vaksin' => $request->jenis_vaksin,
            'tanggal_imunisasi' => $request->tanggal_imunisasi,
            'jenis_imunisasi' => $request->jenis_imunisasi,
        ]);

        DB::commit();

        return redirect()->route('admin.jadwal-imunisasi.index')
            ->with('success', 'Jadwal berhasil ditambahkan. Umur balita saat imunisasi: ' . $umurFormat);

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
        'jenis_imunisasi' => 'required|string',
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
        'jenis_imunisasi' => $request->jenis_imunisasi,
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

        // Cek apakah data sudah ada di riwayat
        $riwayat = RiwayatImunisasi::where('jadwal_imunisasi_id', $jadwal->id)
                        ->where('balita_id', $balita->id)
                        ->first();

        if ($riwayat) {
            // Jika sudah ada, update
            $riwayat->update([
                'tanggal_imunisasi' => now(),
                'status'            => $status,
                'suhu'              => $balita->suhu,
                'berat_badan'       => $balita->berat_badan,
                'tinggi_badan'      => $balita->tinggi_badan,
            ]);
        } else {
            // Jika belum ada, buat baru
            RiwayatImunisasi::create([
                'balita_id'           => $balita->id,
                'jadwal_imunisasi_id' => $jadwal->id,
                'tanggal_imunisasi'   => now(),
                'jenis_imunisasi'     => $jadwal->jenis_imunisasi,
                'jenis_vaksin'        => $jadwal->jenis_vaksin,
                'status'              => $status,
                'suhu'                => $balita->suhu,
                'berat_badan'         => $balita->berat_badan,
                'tinggi_badan'        => $balita->tinggi_badan,
            ]);
        }

        // Hapus dari jadwal
        $jadwal->delete();

        DB::commit();
        return redirect()->back()->with('success', '✅ Status imunisasi berhasil disimpan ke riwayat.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("Gagal update status ke riwayat: " . $e->getMessage());
        return redirect()->back()->with('error', '❌ Gagal memperbarui status imunisasi.');
    }
}





  public function panggil($id)
{
    $jadwalImunisasi = JadwalImunisasi::with('balita.orangtua')->findOrFail($id);
    $balita = $jadwalImunisasi->balita;
    $orangtua = $balita->orangtua;
    $jadwalTanggal = Carbon::parse($jadwalImunisasi->tanggal_imunisasi);
    $hariIni = Carbon::now();

    if ($orangtua && $orangtua->no_telepon) {
        // Format nomor WA
        $noHp = preg_replace('/[^0-9]/', '', $orangtua->no_telepon);
        $noWa = preg_replace('/^0/', '62', $noHp);

        // Buat pesan
        $pesan = "👶 *Notifikasi Imunisasi*\n\n" .
                 "Halo Bapak/Ibu,\n\n" .
                 "Imunisasi untuk balita *{$balita->nama}* dijadwalkan pada:\n" .
                 "*Tanggal:* " . $jadwalTanggal->format('d-m-Y') . "\n" .
                 "*Jenis Vaksin:* {$jadwalImunisasi->jenis_vaksin}\n\n" .
                 "Silakan hadir di klinik sesuai jadwal.\nTerima kasih 🙏";

        // Kirim pesan via Fonnte (gunakan token dari .env)
        $token = env('FONNTE_TOKEN'); // Pastikan kamu sudah set ini di file .env
        if (!$token) {
            return back()->with('error', '❌ Token Fonnte belum diatur di file .env.');
        }

        $response = Http::withHeaders([
            'Authorization' => $token
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $noWa,
            'message' => $pesan,
            'countryCode' => '62', // optional jika sudah format 62
        ]);

        Log::info('📤 Fonnte Response:', $response->json());

        if (isset($response['status']) && $response['status'] === true) {
            // Status imunisasi
            $status = 'belum imunisasi';
           if ($hariIni->startOfDay()->gt($jadwalTanggal->startOfDay())) {
    $terlambat = (int) $hariIni->startOfDay()->diffInDays($jadwalTanggal->startOfDay());
    $status = "belum imunisasi (terlambat {$terlambat} hari)";
}


            // Cegah duplikat
            $sudahAda = RiwayatImunisasi::where('jadwal_imunisasi_id', $jadwalImunisasi->id)->exists();

            if (! $sudahAda) {
                RiwayatImunisasi::create([
                    'balita_id'           => $balita->id,
                    'jadwal_imunisasi_id' => $jadwalImunisasi->id,
                    'tanggal_imunisasi'   => $jadwalImunisasi->tanggal_imunisasi,
                    'jenis_imunisasi'     => $jadwalImunisasi->jenis_imunisasi,                   
                    'jenis_vaksin'        => $jadwalImunisasi->jenis_vaksin,
                    'status'              => $status,
                    'suhu'                => $balita->suhu,
                    'tinggi_badan'        => $balita->tinggi_badan,
                    'berat_badan'         => $balita->berat_badan,
                ]);
            }

            return redirect()->route('admin.jadwal-imunisasi.index')
                ->with('success', '✅ Notifikasi Fonnte berhasil dikirim & data disalin ke riwayat imunisasi.');
        } else {
            return back()->with('error', '❌ Gagal mengirim notifikasi via Fonnte. Cek token dan saldo akun Fonnte.');
        }
    }

    return back()->with('error', '❌ Nomor telepon orang tua tidak tersedia atau tidak valid.');
}






}
