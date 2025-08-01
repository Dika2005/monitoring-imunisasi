@extends('layouts.user')

@section('content')
<div class="container mt-4 text-white">

    {{-- Gambar banner landscape --}}
    <div class="text-center mb-4">
        <img src="{{ asset('images/banner-imunisasi.png') }}" alt="Banner Imunisasi" class="img-fluid rounded shadow" style="max-height: 250px; object-fit: cover; width: 100%;">
    </div>

    {{-- Heading --}}
    <h2 class="mb-4 text-center">Selamat Datang di Dashboard Imunisasi</h2>

    {{-- Ringkasan jumlah imunisasi --}}
   <div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card bg-success text-white shadow h-100 py-2">
            <div class="card-body text-center">
                <div class="h5">Sudah</div>
                <div class="h4 fw-bold">{{ $jumlahSelesai }}</div>
                <div class="small">Imunisasi Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card bg-warning text-dark shadow h-100 py-2">
            <div class="card-body text-center">
                <div class="h5">Belum</div>
                <div class="h4 fw-bold">{{ $jumlahBelum }}</div>
                <div class="small">Imunisasi Belum Dilakukan</div>
            </div>
        </div>
    </div>
</div>

    {{-- Tabel Jadwal Imunisasi Terdekat --}}
    <div class="card bg-dark shadow mb-5">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Jadwal Imunisasi Terdekat</h5>
        </div>
        <div class="card-body p-0">
            @if ($jadwalTerdekat->count() > 0)
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Balita</th>
                                <th>Jenis Imunisasi</th>
                                <th>Tanggal Imunisasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwalTerdekat as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->balita->nama }}</td>
                                    <td>{{ $item->jenis_imunisasi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                                    <td>
                                        @if(strtolower($item->status) === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-3 text-center text-muted">
                    <p>Tidak ada jadwal imunisasi terdekat.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
