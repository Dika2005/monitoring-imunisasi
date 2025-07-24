@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Vaksin</h2>

    <form action="{{ route('admin.ketersediaan-vaksin.update', $vaksin->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nama Vaksin</label>
            <input type="text" name="nama_vaksin" value="{{ $vaksin->nama_vaksin }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ $vaksin->stok }}" class="form-control" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.ketersediaan-vaksin.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
