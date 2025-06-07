@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">Edit Data Balita</h2>
    <form action="{{ route('admin.balita.update', $balitum->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Balita</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $balitum->nama }}" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $balitum->tanggal_lahir }}" required>
        </div>
        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="laki-laki" {{ $balitum->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="perempuan" {{ $balitum->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="user_email" class="form-label">Email Orang Tua / Wali</label>
            <input type="email" class="form-control" id="user_email" name="user_email" placeholder="contoh@gmail.com" value="{{ $balitum->user->email ?? '' }}" readonly>
            <div class="form-text">Jika email belum terdaftar, sistem akan membuat akun baru untuk email ini.</div>
            @error('user_email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ $balitum->alamat }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.balita.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
