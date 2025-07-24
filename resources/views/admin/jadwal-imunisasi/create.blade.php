@extends('layouts.admin')

@section('content')
    <div class="container mt-5 d-flex justify-content-center">
        <div class="w-50 bg-white text-dark p-4 rounded shadow">
            <h2 class="text-center mb-4">Tambah Jadwal Imunisasi</h2>

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
    <select name="jenis_vaksin" id="jenis_vaksin" class="form-select" required>
        <option value="">-- Pilih Vaksin --</option>
        @foreach ($vaksins as $vaksin)
            <option value="{{ $vaksin->nama_vaksin }}">{{ $vaksin->nama_vaksin }} (Stok: {{ $vaksin->stok }})</option>
        @endforeach
    </select>
</div>

                <div class="mb-3">
                    <label for="tanggal_imunisasi" class="form-label">Tanggal Imunisasi</label>
                    <input type="date" class="form-control" id="tanggal_imunisasi" name="tanggal_imunisasi" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.jadwal-imunisasi.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
