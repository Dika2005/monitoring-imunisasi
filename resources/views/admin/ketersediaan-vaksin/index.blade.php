@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Ketersediaan Vaksin</h2>
    <a href="{{ route('admin.ketersediaan-vaksin.create') }}" class="btn btn-success mb-3">+ Tambah Vaksin</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-dark table-bordered text-center align-middle">
            <thead class="sticky-top bg-dark">
                <tr>
                    <th>No</th>
                    <th>Jenis Imunisasi</th> 
                    <th>Nama Vaksin</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $vaksin)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vaksin->jenis_imunisasi }}</td>
                    <td>{{ $vaksin->nama_vaksin }}</td>
                    <td>{{ $vaksin->stok }}</td>
                    <td>
                        <a href="{{ route('admin.ketersediaan-vaksin.edit', $vaksin->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('admin.ketersediaan-vaksin.destroy', $vaksin->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
