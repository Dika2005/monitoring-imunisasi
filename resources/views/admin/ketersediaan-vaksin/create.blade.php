@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Tambah Data Vaksin</h2>

    <form action="{{ route('admin.ketersediaan-vaksin.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Vaksin</label>
            <input type="text" name="nama_vaksin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.ketersediaan-vaksin.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
