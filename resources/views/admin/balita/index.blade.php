@extends('layouts.admin')

@section('content')
    <div class="container mt-4"> {{-- Tambahkan container untuk konsistensi layout --}}
        <h2>Daftar Data Balita</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.balita.create') }}" class="btn btn-primary mb-3">Tambah Balita Baru</a>

        @if($balitas->isEmpty())
            <div class="alert alert-info" role="alert">
                Belum ada data balita.
            </div>
        @else
            <div class="table-responsive">
                {{-- PERUBAHAN DI SINI: Menambahkan kelas Bootstrap untuk styling dark table --}}
                <table class="table table-dark table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Balita</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Orang Tua (User)</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($balitas as $balita)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $balita->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($balita->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                                <td>{{ ucfirst($balita->jenis_kelamin) }}</td>
                                {{-- Pastikan relasi 'user' di model Balita sudah di-eager load di controller AdminBalitaController --}}
                                <td>{{ $balita->user->name ?? 'N/A' }}</td>
                                <td>{{ $balita->alamat }}</td>
                                <td>
                                    <a href="{{ route('admin.balita.edit', $balita->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.balita.destroy', $balita->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data balita ini?');">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div> {{-- Penutup div container --}}
@endsection