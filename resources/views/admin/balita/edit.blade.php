@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Balita</h2>

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

    <form action="{{ route('admin.balita.update', $balita->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Balita</label>
        <input type="text" class="form-control" id="nama" name="nama"
            value="{{ old('nama', $balita->nama) }}" required>
    </div>

    <div class="mb-3">
        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
            <option value="">-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin', $balita->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="suhu_badan" class="form-label">Suhu Badan (°C)</label>
        <input type="number" step="0.1" class="form-control" name="suhu_badan"
            value="{{ old('suhu_badan', $balita->suhu_badan) }}" min="0" required>
    </div>

    <div class="mb-3">
        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
        <input type="number" class="form-control" name="tinggi_badan"
            value="{{ old('tinggi_badan', $balita->tinggi_badan) }}" min="0" required>
    </div>

    <div class="mb-3">
        <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
        <input type="number" step="0.1" class="form-control" name="berat_badan"
            value="{{ old('berat_badan', $balita->berat_badan) }}" min="0" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('admin.balita.index') }}" class="btn btn-secondary">Kembali</a>
</form>

</div>
@endsection
