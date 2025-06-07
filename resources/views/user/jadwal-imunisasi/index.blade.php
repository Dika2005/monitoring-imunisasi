@extends('layouts.user')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-white">Selamat Datang, {{ Auth::user()->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Data Jadwal Imunisasi --}}
    <div class="card">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-calendar-alt"></i> Jadwal Imunisasi Balita Anda
        </div>
        <div class="card-body bg-dark text-white">
            @if($jadwal_imunisasi_user->isEmpty())
                <div class="alert alert-info text-center">
                    Tidak ada jadwal imunisasi untuk balita Anda.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Balita</th>
                                <th>Jenis Vaksin</th>
                                <th>Tanggal Imunisasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal_imunisasi_user as $jadwal)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jadwal->balita->nama ?? 'N/A' }}</td>
                                    <td>{{ $jadwal->jenis_vaksin }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
