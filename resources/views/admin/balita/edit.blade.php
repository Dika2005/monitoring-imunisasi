@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Balita</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.balita.update', $balita->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Balita</label>
            <input type="text" class="form-control" id="nama" name="nama" 
                   value="{{ old('nama', $balita->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                   value="{{ old('tanggal_lahir', $balita->tanggal_lahir) }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="2" required>{{ old('alamat', $balita->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="no_telepon" class="form-label">No Telepon Orang Tua</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" 
                   value="{{ old('no_telepon', $balita->no_telepon) }}" required
                   pattern="[0-9]+" maxlength="15" 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Orang Tua (User)</label>
            <select class="form-control" name="user_id" id="user_id" required>
                <option value="" disabled>-- Pilih Orang Tua --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == old('user_id', $balita->user_id) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('admin.balita.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
