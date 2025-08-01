@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Tambah Data Vaksin</h2>

    <form action="{{ route('admin.ketersediaan-vaksin.store') }}" method="POST">
        @csrf

        @php
            $vaksinMap = [
                'Hepatitis B' => ['Vaksin Hepatitis B'],
                'BCG' => ['Vaksin BCG'],
                'Polio' => ['OPV (Polio Tetes)', 'IPV (Polio Suntik)'],
                'DPT-HB-Hib' => ['Vaksin Pentavalent (DPT-HB-Hib)'],
                'Campak-Rubella' => ['MR (Measles-Rubella)'],
                'PCV' => ['Vaksin Pneumokokus (PCV)'],
                'Rotavirus' => ['Vaksin Rotavirus (oral)'],
                'JE' => ['Vaksin Japanese Encephalitis (JE)'],
                'HPV' => ['Vaksin HPV (bivalen)', 'Vaksin HPV (quadrivalent)'],

                // Tambahan dianjurkan
                'Influenza' => ['Vaksin Influenza'],
                'Tifoid' => ['Vaksin Tifoid'],
                'Hepatitis A' => ['Vaksin Hepatitis A'],
                'Varisela' => ['Vaksin Cacar Air (Varisela)'],
                'Dengue' => ['Vaksin Dengue (Qdenga)', 'Vaksin Dengue (DENVAX)'],
            ];
        @endphp

        {{-- Dropdown Jenis Imunisasi --}}
        <div class="mb-3">
            <label>Jenis Imunisasi</label>
            <select name="jenis_imunisasi" id="jenisImunisasi" class="form-control" required>
                <option value="">-- Pilih Jenis Imunisasi --</option>
                @foreach(array_keys($vaksinMap) as $jenis)
                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                @endforeach
            </select>
        </div>

        {{-- Dropdown Nama Vaksin --}}
        <div class="mb-3">
            <label>Nama Vaksin</label>
            <select name="nama_vaksin" id="namaVaksin" class="form-control" required>
                <option value="">-- Pilih Nama Vaksin --</option>
            </select>
        </div>

        {{-- Input Stok --}}
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.ketersediaan-vaksin.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

{{-- Script untuk dynamic dropdown --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const vaksinMap = @json($vaksinMap);
        const jenisSelect = document.getElementById('jenisImunisasi');
        const vaksinSelect = document.getElementById('namaVaksin');

        jenisSelect.addEventListener('change', function () {
            const selectedJenis = this.value;
            const daftarVaksin = vaksinMap[selectedJenis] || [];

            vaksinSelect.innerHTML = '<option value="">-- Pilih Nama Vaksin --</option>';

            daftarVaksin.forEach(function (vaksin) {
                const option = document.createElement('option');
                option.value = vaksin;
                option.text = vaksin;
                vaksinSelect.appendChild(option);
            });
        });
    });
</script>
@endsection
