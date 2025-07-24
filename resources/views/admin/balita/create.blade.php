@extends('layouts.admin')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="w-50 bg-white text-dark p-4 rounded shadow">
        <h2 class="text-center mb-4">Tambah Data Balita</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan!</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.balita.store') }}" method="POST">
    @csrf

    {{-- Nama Balita --}}
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Balita</label>
        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required>
    </div>

    {{-- Tanggal Lahir --}}
    <div class="mb-3">
        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
    </div>

    {{-- Jenis Kelamin --}}
    <div class="mb-3">
        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>

    {{-- Suhu badan --}}
    <div class="mb-3">
        <label for="suhu_badan" class="form-label">Suhu badan (°C)</label>
        <input type="number" step="0.1" class="form-control" id="suhu_badan" name="suhu_badan" value="{{ old('suhu_badan') }}">
    </div>

    {{-- Tinggi Badan --}}
    <div class="mb-3">
        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
        <input type="number" step="0.1" class="form-control" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan') }}">
    </div>

    {{-- Berat Badan --}}
    <div class="mb-3">
        <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
        <input type="number" step="0.1" class="form-control" id="berat_badan" name="berat_badan" value="{{ old('berat_badan') }}">
    </div>

    {{-- Nama Orang Tua --}}
    <div class="mb-3">
    <label for="orangtua_id" class="form-label">Nama Orang Tua</label>
    <select class="form-select" id="orangtua_id" name="orangtua_id" required>
        <option value="">-- Pilih Orang Tua --</option>
        @foreach ($orangtuas as $orangtua)
            <option value="{{ $orangtua->id }}" {{ old('orangtua_id') == $orangtua->id ? 'selected' : '' }}>
                {{ $orangtua->nama }} - {{ $orangtua->no_telepon }}
            </option>
        @endforeach
    </select>
</div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.balita.index') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-success">Simpan</button>
    </div>
</form>

    </div>
</div>
@endsection
