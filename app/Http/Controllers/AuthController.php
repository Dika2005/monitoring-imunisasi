<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin() {
        return view('auth.login');
    }

    // Tampilkan form register
    public function showRegister() {
        return view('auth.register');
    }

    // Proses login
    public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard'); // nanti kita buat rute ini
        }
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->withInput();
}


    // Proses register
    // Proses register
public function register(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'no_telepon' => 'required|string|max:20',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // Buat user baru
    $user = User::create([
        'name' => $request->nama,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Cek apakah email sudah ada di orang tua, jika belum buat baru
    $orangTua = OrangTua::where('email', $request->email)->first();
    if (!$orangTua) {
        OrangTua::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => '-', // bisa kosong atau kamu sesuaikan
        ]);
    }

    // Login otomatis user setelah register
    auth()->login($user);

    return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
}

    // Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}
