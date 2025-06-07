@extends('layouts.user') {{-- Menggunakan layout user yang sudah dibuat --}}

@section('page_title', 'Laporan Imunisasi Anda') {{-- Judul halaman untuk tab browser --}}

@section('content')
<div class="container mt-4">
    <h2 class="text-white mb-4">Laporan Imunisasi Balita Anda</h2>

    @if($laporans->count() > 0)
    <div class="card bg-dark text-white shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Balita</th>
                            <th>Nama Vaksin</th> {{-- Header untuk Nama Vaksin --}}
                            <th>Tanggal Imunisasi</th>
                            <th>Status</th>
                            {{-- TH untuk "Catatan" DIHAPUS --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $laporan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- Asumsi ada relasi 'balita' di model LaporanImunisasi dan sudah di-eager load --}}
                            <td>{{ $laporan->balita->nama ?? 'N/A' }}</td>
                            {{-- PERBAIKAN: Menggunakan $laporan->jenis_vaksin (sesuai nama kolom di DB) --}}
                            <td>{{ $laporan->jenis_vaksin }}</td>
                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td>
                                {{-- Menampilkan status dengan badge Bootstrap --}}
                                @if($laporan->status === 'selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-info">{{ ucfirst($laporan->status) }}</span>
                                @endif
                            </td>
                            {{-- TD untuk "Catatan" DIHAPUS --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <i class="fas fa-info-circle me-2"></i> Belum ada laporan imunisasi yang tersedia untuk balita Anda.
    </div>
    @endif
</div>
@endsection