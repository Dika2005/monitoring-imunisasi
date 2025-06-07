@extends('layouts.admin')

@section('content')
    <div class="container mt-4"> {{-- Tambahkan container untuk konsistensi layout --}}
        <h2>Laporan Imunisasi</h2>

        <div class="table-responsive">
            {{-- PERUBAHAN DI SINI: Menambahkan kelas Bootstrap untuk styling dark table --}}
            <table class="table table-dark table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Balita</th>
                        <th>Jenis Vaksin</th>
                        <th>Tanggal Imunisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- PASTIKAN NAMA VARIABEL INI SAMA DENGAN YANG DIKIRIM DARI CONTROLLER ADMIN LAPORAN --}}
                    {{-- Jika dari controller dikirimnya `$laporan_imunisasi`, maka ini sudah benar. --}}
                    {{-- Jika dari controller dikirimnya `$laporans`, maka ganti `$laporan_imunisasi` menjadi `$laporans`. --}}
                    @forelse ($laporan_imunisasi as $laporan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $laporan->balita->nama }}</td>
                            <td>{{ $laporan->jenis_vaksin }}</td>
                            {{-- Memformat tanggal agar lebih mudah dibaca --}}
                            <td>{{ \Carbon\Carbon::parse($laporan->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td>{{ $laporan->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data laporan imunisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div> {{-- Penutup div container --}}
@endsection