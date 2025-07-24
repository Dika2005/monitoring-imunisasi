@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h3>Data Orang Tua</h3>
        <a href="{{ route('admin.orangtua.create') }}" class="btn btn-success">Tambah Orang Tua</a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('admin.orangtua.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama orang tua..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email (Gmail)</th>
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orangtuas as $key => $orangTua)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $orangTua->nama }}</td>
                <td>{{ $orangTua->email ?? '-' }}</td>
                <td>{{ $orangTua->no_telepon }}</td>
                <td>
                    <a href="{{ route('admin.orangtua.edit', $orangTua->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.orangtua.destroy', $orangTua->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Data tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
