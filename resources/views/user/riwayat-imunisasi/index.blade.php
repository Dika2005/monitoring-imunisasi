@extends('layouts.user')

@php
    use Carbon\Carbon;
@endphp

@section('content')
<div class="container mt-4">
    <h2>Riwayat Imunisasi</h2>

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-dark table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Balita</th>
                    <th>Umur</th>
                    <th>Nama Orang Tua</th>
                    <th>No Telepon</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Jenis Imunisasi</th>
                    <th>Suhu</th>
                    <th>Berat</th>
                    <th>Tinggi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayats as $key => $item)
                    @php
                        $status = strtolower($item->status);
                    @endphp

                    @if ($status === 'selesai' || str_contains($status, 'terlambat'))
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->balita->nama }}</td>
                            <td>
                                @php
                                    $tanggalLahir = Carbon::parse($item->balita->tanggal_lahir);
                                    $tanggalImunisasi = Carbon::parse($item->tanggal_imunisasi);
                                    $selisih = $tanggalLahir->diff($tanggalImunisasi);
                                    $umur = '';

                                    if ($selisih->y > 0) {
                                        $umur .= $selisih->y . ' tahun ';
                                    }

                                    if ($selisih->m > 0) {
                                        $umur .= $selisih->m . ' bulan ';
                                    }

                                    if ($selisih->y === 0 && $selisih->m === 0) {
                                        $umur .= $selisih->d . ' hari';
                                    }

                                    echo trim($umur);
                                @endphp
                            </td>
                            <td>{{ $item->balita->orangtua->nama ?? '-' }}</td>
                            <td>{{ $item->balita->orangtua->no_telepon ?? '-' }}</td>
                            <td>{{ Carbon::parse($item->tanggal_imunisasi)->format('d-m-Y') }}</td>
                            <td>{{ $item->jenis_imunisasi }}</td>
                            <td>{{ $item->balita->suhu_badan }} °C</td>
                            <td>{{ $item->balita->berat_badan }} kg</td>
                            <td>{{ $item->balita->tinggi_badan }} cm</td>
                            <td>
                                @if (str_contains($status, 'terlambat'))
                                    @php
                                        preg_match('/\d+/', $status, $matches);
                                        $hariTerlambat = $matches[0] ?? null;
                                    @endphp
                                    Terlambat {{ $hariTerlambat }} hari
                                @else
                                    {{ ucfirst($status) }}
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
