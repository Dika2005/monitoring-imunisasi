@extends('layouts.admin')

@section('content')
<div class="container mt-4 w-50">
    <h3 class="mb-3">Tambah Data Orang Tua</h3>

    {{-- Tampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.orangtua.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email (Gmail)</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="contoh: orangtua@gmail.com" required>
        </div>

        <div class="mb-3">
            <label for="no_telepon" class="form-label">No Telepon</label>
            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.orangtua.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>   
        </div>
    </form>
</div>
@endsection
