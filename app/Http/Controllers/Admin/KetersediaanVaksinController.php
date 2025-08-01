<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KetersediaanVaksin;

class KetersediaanVaksinController extends Controller
{
    public function index()
    {
        $data = KetersediaanVaksin::all();
        return view('admin.ketersediaan-vaksin.index', compact('data'));
    }

    public function create()
    {
        return view('admin.ketersediaan-vaksin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vaksin' => 'required',
            'jenis_imunisasi' => 'required|string',
            'stok' => 'required|integer',
        ]);

        KetersediaanVaksin::create($request->all());

        return redirect()->route('admin.ketersediaan-vaksin.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $vaksin = KetersediaanVaksin::findOrFail($id);
        return view('admin.ketersediaan-vaksin.edit', compact('vaksin'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_vaksin' => 'required',
            'jenis_imunisasi' => 'required|string',
            'stok' => 'required|integer',
        ]);

        $vaksin = KetersediaanVaksin::findOrFail($id);
        $vaksin->update($request->all());

        return redirect()->route('admin.ketersediaan-vaksin.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $vaksin = KetersediaanVaksin::findOrFail($id);
        $vaksin->delete();

        return redirect()->route('admin.ketersediaan-vaksin.index')->with('success', 'Data berhasil dihapus.');
    }
}
