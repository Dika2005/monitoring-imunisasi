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
                            <option value="{{ $balita->id }}" data-tanggallahir="{{ $balita->tanggal_lahir }}">{{ $balita->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jenis_imunisasi" class="form-label">Jenis Imunisasi</label>
                    <select id="jenis_imunisasi" name="jenis_imunisasi" class="form-select" required>
                        <option value="">-- Pilih Jenis Imunisasi --</option>
                        @foreach ($vaksins->pluck('jenis_imunisasi')->unique() as $jenis)
                            <option value="{{ $jenis }}">{{ $jenis }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jenis_vaksin" class="form-label">Nama Vaksin</label>
                    <select name="jenis_vaksin" id="jenis_vaksin" class="form-select" required>
                        <option value="">-- Pilih Vaksin --</option>
                        {{-- Akan diisi oleh JS --}}
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

            <script>
                const allVaksins = @json($vaksins);

                document.getElementById('jenis_imunisasi').addEventListener('change', function () {
                    const jenisTerpilih = this.value;
                    const vaksinSelect = document.getElementById('jenis_vaksin');
                    vaksinSelect.innerHTML = '<option value="">-- Pilih Vaksin --</option>'; // reset

                    allVaksins.forEach(vaksin => {
                        if (vaksin.jenis_imunisasi === jenisTerpilih) {
                            const option = document.createElement('option');
                            option.value = vaksin.nama_vaksin;
                            option.text = `${vaksin.nama_vaksin} (Stok: ${vaksin.stok})`;
                            vaksinSelect.appendChild(option);
                        }
                    });
                });
            </script>
        </div>
    </div>
@endsection
