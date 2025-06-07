@extends('layouts.user') {{-- Atau sesuaikan dengan layout kamu --}}


@section('content')
<div class="container mt-4">
    <h1 class="text-white mb-4">Selamat Datang, {{ $user->name }}</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-dark text-white mb-4">
                <div class="card-header">Jadwal Imunisasi</div>
                <div class="card-body">
                    @if($jadwal->count())
                        <ul class="list-group">
                            @foreach($jadwal as $item)
                                <li class="list-group-item bg-secondary text-white">
                                    {{ $item->nama_vaksin }} - {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Tidak ada jadwal imunisasi.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark text-white mb-4">
                <div class="card-header">Laporan Imunisasi</div>
                <div class="card-body">
                    @if($laporan->count())
                        <ul class="list-group">
                            @foreach($laporan as $item)
                                <li class="list-group-item bg-secondary text-white">
                                    {{ $item->nama_vaksin }} - Status: 
                                    <strong>{{ $item->status ?? 'Selesai' }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Belum ada laporan imunisasi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
