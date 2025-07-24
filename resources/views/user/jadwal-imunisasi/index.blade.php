@extends('layouts.user')

@section('content')
<h2>Jadwal Imunisasi</h2>
<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Balita</th>
            <th>Tanggal Imunisasi</th>
            <th>Jenis Vaksin</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jadwals as $key => $jadwal)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $jadwal->balita->nama }}</td>
            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->format('d-m-Y') }}</td>
            <td>{{ $jadwal->jenis_vaksin }}</td>
            <td>{{ ucfirst($jadwal->status) }}</td>
            <td><a href="{{ route('user.jadwal-imunisasi.show', $jadwal->id) }}" class="btn btn-sm btn-info">Detail</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
