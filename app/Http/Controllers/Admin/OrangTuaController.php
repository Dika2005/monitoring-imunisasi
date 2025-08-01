<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrangTuaController extends Controller
{
    public function index()
    {
        $orangtuas = OrangTua::all(); // ambil semua data
        return view('admin.orangtua.index', compact('orangtuas'));
    }

    public function create()
    {
        return view('admin.orangtua.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'no_telepon' => 'required|string|max:20',
        'password' => 'required|string|min:6',
    ]);

    // Simpan dulu ke tabel users
    $user = User::create([
        'name' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Kemudian simpan ke tabel orang tua
    OrangTua::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'no_telepon' => $request->no_telepon,
        'password' => bcrypt($request->password),
        'user_id' => $user->id, // koneksi ke tabel users
    ]);

    return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil ditambahkan');
}

    public function edit(OrangTua $orangtua)
    {
        return view('admin.orangtua.edit', compact('orangtua'));
    }

    public function update(Request $request, OrangTua $orangtua)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:orang_tuas,email,' . $orangtua->id,
            'no_telepon' => 'required|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $orangtua->nama = $request->nama;
        $orangtua->email = $request->email;
        $orangtua->no_telepon = $request->no_telepon;

        // Update password hanya jika tidak kosong
        if ($request->filled('password')) {
            $orangtua->password = Hash::make($request->password);
        }

        $orangtua->save();

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $orangTua = OrangTua::findOrFail($id);

        // Hapus juga user terkait jika ada
        if ($orangTua->user_id) {
            User::where('id', $orangTua->user_id)->delete();
        }

        $orangTua->delete();

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil dihapus.');
    }
}
