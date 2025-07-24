@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Riwayat Imunisasi</h2>

    <form method="GET" action="{{ route('admin.riwayat-imunisasi.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control bg-dark text-white border-secondary"
                   placeholder="Cari Nama Balita" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="vaksin" class="form-select bg-dark text-white border-secondary">
                <option value="">Semua Vaksin</option>
                @foreach ($daftar_jenis_vaksin as $jenis)
                    <option value="{{ $jenis }}" {{ request('vaksin') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select bg-dark text-white border-secondary">
                <option value="">Semua Status</option>
                <option value="belum imunisasi" {{ request('status') == 'belum imunisasi' ? 'selected' : '' }}>Belum Imunisasi</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Balita</th>
                    <th>Umur</th>
                    <th>Nama Orang Tua</th>
                    <th>No. Telepon</th>
                    <th>Suhu (°C)</th>
                    <th>Tinggi (cm)</th>
                    <th>Berat (kg)</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Jenis Vaksin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat_imunisasi as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->balita->nama }}</td>
                        <td>{{ $item->umur_format }}</td>
                        <td>{{ optional($item->balita->orangtua)->nama ?? '-' }}</td>
                        <td>{{ optional($item->balita->orangtua)->no_telepon ?? '-' }}</td>
                        <td>{{ $item->balita->suhu_badan ? $item->balita->suhu_badan . ' °C' : '-' }}</td>
                        <td>{{ $item->balita->tinggi_badan ? $item->balita->tinggi_badan . ' cm' : '-' }}</td>
                        <td>{{ $item->balita->berat_badan ? $item->balita->berat_badan . ' kg' : '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                        <td>{{ $item->jenis_vaksin }}</td>
                        <td>
                            @php
                                $status = strtolower($item->status);
                            @endphp

                            @if (str_contains($status, 'terlambat'))
                                <span class="badge bg-warning text-dark">{{ ucfirst($status) }}</span>
                            @elseif ($status == 'selesai')
                                <span class="badge bg-success">{{ ucfirst($status) }}</span>
                            @elseif ($status == 'belum imunisasi')
                                <span class="badge bg-danger">{{ ucfirst($status) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">Tidak ada data riwayat imunisasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
