@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">Jadwal Imunisasi Balita</h2>

    @php
        // Filter jadwal yang tidak selesai
        $jadwalBelumSelesai = $jadwals->filter(function ($jadwal) {
            return !in_array(strtolower($jadwal->status), ['selesai']);
        });
    @endphp

    @if($jadwalBelumSelesai->isEmpty())
        <div class="alert alert-warning text-center">
            Belum ada jadwal imunisasi untuk balita Anda.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered text-center align-middle w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Balita</th>
                        <th>Tanggal Imunisasi</th>
                        <th>Jenis Imunisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
@foreach ($jadwalBelumSelesai as $key => $jadwal)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $jadwal->balita->nama }}</td>
    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
    <td>{{ $jadwal->jenis_imunisasi }}</td>
    <td>
        @if(Str::contains(strtolower($jadwal->status), 'terlambat'))
            <span class="badge bg-danger">{{ $jadwal->status }}</span>
        @else
            <span class="badge bg-warning text-dark">{{ $jadwal->status }}</span>
        @endif
    </td>
</tr>
@endforeach
</tbody>

            </table>
        </div>
    @endif
</div>
@endsection
