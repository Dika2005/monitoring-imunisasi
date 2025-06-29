@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h2>Riwayat Imunisasi</h2>

        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Balita</th>
                        <th>Umur</th>
                        <th>Nama Orang Tua</th>
                        <th>Jenis Vaksin</th>
                        <th>Tanggal Imunisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat_imunisasi as $riwayat)
                        @php
                            $umur = '-';
                            if (isset($riwayat->balita->tanggal_lahir) && isset($riwayat->tanggal_imunisasi)) {
                                $lahir = \Carbon\Carbon::parse($riwayat->balita->tanggal_lahir);
                                $tanggalImunisasi = \Carbon\Carbon::parse($riwayat->tanggal_imunisasi);

                                $diff = $lahir->diff($tanggalImunisasi);

                                $parts = [];
                                if ($diff->y > 0) $parts[] = $diff->y . ' tahun';
                                if ($diff->m > 0) $parts[] = $diff->m . ' bulan';
                                if ($diff->y === 0 && $diff->m === 0 && $diff->d > 0) $parts[] = $diff->d . ' hari';

                                $umur = count($parts) > 0 ? implode(' ', $parts) : 'Baru lahir';
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $riwayat->balita->nama ?? '-' }}</td>
                            <td>{{ $umur }}</td>
                            <td>{{ optional($riwayat->balita->user)->name ?? '-' }}</td>
                            <td>{{ $riwayat->jenis_vaksin }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td>{{ $riwayat->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data riwayat imunisasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
