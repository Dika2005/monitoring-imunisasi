<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita; // Pastikan ini benar!
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

class BalitaController extends Controller
{
    public function index(): View // Gunakan kelas View yang benar di sini
    {
        // Menampilkan daftar balita
        $balitas = Balita::all(); // Ambil data balita dari database
        return view('admin.balita.index', compact('balitas')); // Kirim data ke view
    }

    public function create()
    {
        // Menampilkan form untuk menambah data balita
        return view('admin.balita.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Data (Sangat Penting)
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
            // Tambahkan validasi untuk kolom lain jika ada
        ]);

        $balita = new Balita();

        $balita->nama = $request->input('nama');
        $balita->tanggal_lahir = $request->input('tanggal_lahir');
        $balita->jenis_kelamin = $request->input('jenis_kelamin');
        $balita->alamat = $request->input('alamat');
    
        $balita->save();

        return Redirect::route('admin.balita.index')->with('success', 'Data balita berhasil ditambahkan.');
        
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit data balita
        $balita = Balita::findOrFail($id);  // Ambil data balita berdasarkan id
        return view('admin.balita.edit', compact('balita')); // Kirim data balita ke view edit
    }

    public function update(Request $request, $id)
    {
        // Update data balita
         $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string',
        ]);

        $balita = Balita::findOrFail($id);
        $balita->nama = $request->nama;
        $balita->tanggal_lahir = $request->tanggal_lahir;
        $balita->jenis_kelamin = $request->jenis_kelamin;
        $balita->alamat = $request->alamat;
        $balita->save();

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil diupdate');
    }

    public function destroy($id)
    {
        // Menghapus data balita
        $balita = Balita::findOrFail($id);
        $balita->delete();

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil dihapus');
    }
}
