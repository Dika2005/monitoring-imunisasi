@extends('layouts.admin')

@section('content')
    <h2>Data Balita</h2>
    <a href="{{ route('admin.balita.create') }}" class="btn btn-primary mb-3">Tambah Balita</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($balitas as $balita)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $balita->nama }}</td>
                        <td>{{ $balita->tanggal_lahir }}</td>
                        <td>{{ $balita->jenis_kelamin }}</td>
                        <td>{{ $balita->alamat }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.balita.edit', $balita->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.balita.destroy', $balita->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data balita.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
