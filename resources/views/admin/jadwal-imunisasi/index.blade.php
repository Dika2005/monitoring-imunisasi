@extends('layouts.admin')

@section('content')
    <h2>Data Jadwal Imunisasi</h2>
    <a href="{{ route('admin.jadwal-imunisasi.create') }}" class="btn btn-primary mb-3">Tambah Jadwal Imunisasi</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Balita</th>
                    <th>Jenis Vaksin</th>
                    <th>Tanggal Imunisasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal_imunisasi as $jadwal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jadwal->balita->nama }}</td> 
                        <td>{{ $jadwal->jenis_vaksin }}</td>
                        <td>{{ $jadwal->tanggal_imunisasi }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.jadwal-imunisasi.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.jadwal-imunisasi.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                 <form action="{{ route('admin.jadwal-imunisasi.selesai', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data jadwal imunisasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
