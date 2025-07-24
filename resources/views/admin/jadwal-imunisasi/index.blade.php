@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Daftar Jadwal Imunisasi</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.jadwal-imunisasi.index') }}" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="text" name="nama" class="form-control" placeholder="Cari Nama Balita" value="{{ request('nama') }}">
        </div>

        <div class="col-md-3">
            <select name="jenis_vaksin" class="form-select">
                <option value="">-- Filter Jenis Vaksin --</option>
                @foreach ($semua_vaksin as $vaksin)
                    <option value="{{ $vaksin }}" {{ request('jenis_vaksin') == $vaksin ? 'selected' : '' }}>
                        {{ $vaksin }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="bulan" class="form-select">
                <option value="">-- Bulan --</option>
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="col-md-1">
            <button type="submit" class="btn btn-success w-100">Filter</button>
        </div>
    </form>

    <a href="{{ route('admin.jadwal-imunisasi.create') }}" class="btn btn-primary mb-3">Tambah Jadwal Baru</a>

    @if ($jadwal_imunisasi->isEmpty())
        <div class="alert alert-info">Belum ada jadwal imunisasi.</div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Balita</th>
                        <th>Tanggal Lahir</th>
                        <th>Umur</th>
                        <th>No. Telepon</th>
                        <th>Nama Orang Tua</th>
                        <th>Jenis Vaksin</th>
                        <th>Tanggal Imunisasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal_imunisasi as $jadwal)
                        @php
                            $balita = $jadwal->balita;
                            $orangTua = $balita->orangTua;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $balita->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                            <td>{{ $balita->umur_format }}</td>
                            <td>{{ $orangTua->no_telepon ?? '-' }}</td>
                            <td>{{ $orangTua->nama ?? '-' }}</td>
                            <td>{{ $jadwal->jenis_vaksin }}</td>
                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td class="d-flex flex-wrap gap-1">
                                <a href="{{ route('admin.jadwal-imunisasi.edit', $jadwal->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.jadwal-imunisasi.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>

                                <a href="{{ route('admin.jadwal-imunisasi.panggil', $jadwal->id) }}" class="btn btn-sm btn-info">
                                    Panggil
                                </a>

                                <form action="{{ route('admin.jadwal-imunisasi.selesai', $jadwal->id) }}" method="POST" onsubmit="return confirm('Tandai sebagai selesai?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success">Selesai</button>
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
