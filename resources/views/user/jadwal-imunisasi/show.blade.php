@extends('layouts.user')

@section('content')
<h2>Detail Jadwal Imunisasi</h2>
<div class="card p-3">
    <p><strong>Balita:</strong> {{ $jadwal->balita->nama }}</p>
    <p><strong>Tanggal Imunisasi:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->format('d-m-Y') }}</p>
    <p><strong>Jenis Vaksin:</strong> {{ $jadwal->jenis_vaksin }}</p>
    <p><strong>Status:</strong> {{ ucfirst($jadwal->status) }}</p>
    <a href="{{ route('user.jadwal-imunisasi.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
