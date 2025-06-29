@extends('layouts.user') {{-- Menggunakan layout user yang sudah dibuat --}}

@section('page_title', 'Riwayat Imunisasi Anda') {{-- Judul halaman untuk tab browser --}}

@section('content')
<div class="container mt-4">
    <h2 class="text-white mb-4">Riwayat Imunisasi Balita Anda</h2>

    @if($riwayats->count() > 0)
    <div class="card bg-dark text-white shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Balita</th>
                            <th>Nama Vaksin</th>
                            <th>Tanggal Imunisasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayats as $riwayat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $riwayat->balita->nama ?? 'N/A' }}</td>
                            <td>{{ $riwayat->jenis_vaksin }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td>
                                @if($riwayat->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-info">{{ ucfirst($riwayat->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i> Belum ada riwayat imunisasi yang tersedia untuk balita Anda.
    </div>
    @endif
</div>
@endsection
