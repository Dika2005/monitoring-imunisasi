@extends('layouts.admin')

@section('content')
    <h2>Laporan Imunisasi</h2>

    <div class="table-responsive">
        <table class="table table-bordered">
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
                @forelse ($laporan_imunisasi as $laporan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $laporan->balita->nama }}</td>
                        <td>{{ $laporan->jenis_vaksin }}</td>
                        <td>{{ $laporan->tanggal_imunisasi }}</td>
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
@endsection
