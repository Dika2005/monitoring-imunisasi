@extends('layouts.admin')

@section('page_title', 'Edit Data Orang Tua')

@section('content')
<div class="container mt-4">
    <div class="card bg-dark text-white">
        <div class="card-header">
            <h4>Edit Data Orang Tua</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orangtua.update', ['orangtua' => $orangtua->id]) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Orang Tua</label>
                    <input type="text" name="nama" class="form-control" 
                        value="{{ old('nama', $orangtua->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        Email (Gmail) <small class="text-warning">(ubah jika lupa password)</small>
                    </label>
                    <input type="email" name="email" class="form-control" 
                        value="{{ old('email', $orangtua->email) }}" placeholder="contoh: orangtua@gmail.com" required>
                </div>

                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon</label>
                    <input type="text" name="no_telepon" class="form-control" 
                        value="{{ old('no_telepon', $orangtua->no_telepon) }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Password Baru <small class="text-muted">(kosongkan jika tidak ingin diubah)</small>
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Password baru (opsional)">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.orangtua.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
