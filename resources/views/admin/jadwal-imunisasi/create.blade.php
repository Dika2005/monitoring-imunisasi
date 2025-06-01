@extends('layouts.admin')

@section('content')
    <h2>Tambah Jadwal Imunisasi</h2>
    <form action="{{ route('admin.jadwal-imunisasi.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="balita_id" class="form-label">Nama Balita</label>
            <select class="form-select" id="balita_id" name="balita_id" required>
                <option value="">Pilih Nama Balita</option>
                @foreach ($balitas as $balita)
                    <option value="{{ $balita->id }}">{{ $balita->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jenis_vaksin" class="form-label">Jenis Vaksin</label>
            <input type="text" class="form-control" id="jenis_vaksin" name="jenis_vaksin" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_imunisasi" class="form-label">Tanggal Imunisasi</label>
            <input type="date" class="form-control" id="tanggal_imunisasi" name="tanggal_imunisasi" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.jadwal-imunisasi.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
