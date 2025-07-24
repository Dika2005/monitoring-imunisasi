@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Data Balita</h3>
        <a href="{{ route('admin.balita.create') }}" class="btn btn-success">Tambah Balita Baru</a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form Pencarian dan Filter --}}
    <form method="GET" action="{{ route('admin.balita.index') }}" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama balita...">
        </div>
        <div class="col-md-4">
            <select name="umur" class="form-select">
                <option value="">Filter Umur</option>
                <option value="0-6" {{ request('umur') == '0-6' ? 'selected' : '' }}>0-6 Bulan</option>
                <option value="7-12" {{ request('umur') == '7-12' ? 'selected' : '' }}>7-12 Bulan</option>
                <option value="13-24" {{ request('umur') == '13-24' ? 'selected' : '' }}>1-2 Tahun</option>
                <option value="25-60" {{ request('umur') == '25-60' ? 'selected' : '' }}>2-5 Tahun</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Terapkan</button>
        </div>
    </form>

    @if($balitas->isEmpty())
        <div class="alert alert-info">Belum ada data balita.</div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered">
                <thead>
    <tr>
        <th>No</th>
        <th>Nama Balita</th>
        <th>Jenis Kelamin</th>
        <th>Tanggal Lahir</th>
        <th>Umur</th>
        <th>Suhu Badan (°C)</th>
        <th>Berat Badan (kg)</th>
        <th>Tinggi Badan (cm)</th>
        <th>Nama Orang Tua</th>
        <th>No Telepon</th>
        <th>Aksi</th>
    </tr>
</thead>

                <tbody>
    @foreach($balitas as $balita)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $balita->nama }}</td>
            <td>{{ $balita->jenis_kelamin }}</td>
            <td>{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->format('d-m-Y') }}</td>
            <td>{{ $balita->umur_format }}</td>
            <td>{{ $balita->suhu_badan ?? '-' }}</td>
            <td>{{ $balita->berat_badan ?? '-' }}</td>
            <td>{{ $balita->tinggi_badan ?? '-' }}</td>
            <td>{{ $balita->orangTua->nama ?? '-' }}</td>
            <td>{{ $balita->orangTua->no_telepon ?? '-' }}</td>

            <td>
                <a href="{{ route('admin.balita.edit', $balita->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('admin.balita.destroy', $balita->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data balita ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

            </table>
        </div>
    @endif
</div>
@endsection
