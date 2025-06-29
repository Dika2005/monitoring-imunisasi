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
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:20', // Validasi untuk no telepon
            'user_id' => 'required|exists:users,id',
        ]);

        Balita::create($validatedData);

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil ditambahkan.');
    }

    public function edit(Balita $balita)
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.balita.edit', compact('balita', 'users'));
    }

    public function update(Request $request, Balita $balita)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|digits_between:10,15|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        $balita->update($validatedData);

        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('admin.balita.index')->with('success', 'Data balita berhasil dihapus.');
    }
}
