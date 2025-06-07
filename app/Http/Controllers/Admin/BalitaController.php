<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\User;

class BalitaController extends Controller
{
    public function index()
    {
        $balitas = Balita::with('user')->latest()->get();
        return view('admin.balita.index', compact('balitas'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.balita.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        Balita::create([
            'nama' => $validatedData['nama'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'alamat' => $validatedData['alamat'],
            'user_id' => $validatedData['user_id'],
        ]);

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit(Balita $balitum)
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.balita.edit', compact('balitum', 'users'));
    }

    public function update(Request $request, Balita $balitum)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'alamat' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        $balitum->update([
            'nama' => $validatedData['nama'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'alamat' => $validatedData['alamat'],
            'user_id' => $validatedData['user_id'],
        ]);

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil diperbarui.');
    }

    public function show(Balita $balitum)
    {
        $balitum->load('user');
        return view('admin.balita.show', compact('balitum'));
    }

    public function destroy(Balita $balitum)
    {
        $balitum->delete();
        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil dihapus.');
    }
}
