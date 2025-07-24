<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\User;
use App\Models\Orangtua;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Balita::query();

        // Pencarian nama balita
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter umur
        if ($request->filled('umur')) {
            $range = explode('-', $request->umur);
            if (count($range) == 2) {
                $min = (int) $range[0];
                $max = (int) $range[1];

                $query->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN ? AND ?', [$min, $max]);
            }
        }

        $balitas = $query->get();

        return view('admin.balita.index', compact('balitas'));
    }

   public function create()
{
    $orangtuas = OrangTua::all(); // ✅ Ambil dari tabel orang_tuas
    return view('admin.balita.create', compact('orangtuas'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string',
            'orangtua_id' => 'required|exists:orang_tuas,id', // ✅ benar
            'suhu_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
        ]);

        Balita::create($request->all());

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit(Balita $balita)
{
    $orangtuas = OrangTua::all(); // ✅ Ambil dari tabel orang_tuas
    return view('admin.balita.edit', compact('balita', 'orangtuas'));
}

    public function update(Request $request, Balita $balita)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'suhu_badan' => 'required|numeric|min:0',
        'tinggi_badan' => 'required|numeric|min:0',
        'berat_badan' => 'required|numeric|min:0',
    ]);

    $balita->update([
        'nama' => $request->nama,
        'jenis_kelamin' => $request->jenis_kelamin,
        'suhu_badan' => $request->suhu_badan,
        'tinggi_badan' => $request->tinggi_badan,
        'berat_badan' => $request->berat_badan,
    ]);

    return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil diperbarui.');
}

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('admin.balita.index')
                         ->with('success', 'Data balita berhasil dihapus.');
    }
}
