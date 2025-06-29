@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2>Data Jadwal Imunisasi</h2>
    <a href="{{ route('admin.jadwal-imunisasi.create') }}" class="btn btn-primary mb-3">Tambah Jadwal Imunisasi</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div id="notificationContainer" class="alert alert-info mt-3 d-none"></div>

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Balita</th>
                    <th>Umur</th>
                    <th>Orang Tua</th>
                    <th>No. Telepon</th>
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
                        <td>{{ $jadwal->balita->umur_format ?? '-' }}</td>
                        <td>{{ $jadwal->balita->user->name ?? '-' }}</td>
                        <td>{{ $jadwal->balita->no_telepon ?? '-' }}</td>
                        <td>{{ $jadwal->jenis_vaksin }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.jadwal-imunisasi.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                <form action="{{ route('admin.jadwal-imunisasi.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        Hapus
                                    </button>
                                </form>

                                <form action="{{ route('admin.jadwal-imunisasi.selesai', $jadwal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>

                                <button
                                    type="button"
                                    class="btn btn-info btn-sm call-button"
                                    data-id="{{ $jadwal->id }}"
                                    data-no-telepon="{{ $jadwal->balita->no_telepon }}"
                                >
                                    Panggil
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data jadwal imunisasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const callButtons = document.querySelectorAll('.call-button');
        const notificationContainer = document.getElementById('notificationContainer');

        function showNotification(message, type) {
            notificationContainer.textContent = message;
            notificationContainer.className = `alert alert-${type} mt-3`;
            notificationContainer.classList.remove('d-none');
            setTimeout(() => {
                notificationContainer.classList.add('d-none');
            }, 5000);
        }

        callButtons.forEach(button => {
            button.addEventListener('click', async function() {
                const jadwalId = this.dataset.id;
                const noTelepon = this.dataset.noTelepon;

                this.disabled = true;
                this.textContent = 'Memanggil...';
                showNotification('Mengirim notifikasi WhatsApp, mohon tunggu...', 'info');

                if (!noTelepon) {
                    showNotification('Gagal: Nomor telepon orang tua tidak ditemukan.', 'danger');
                    this.disabled = false;
                    this.textContent = 'Panggil';
                    return;
                }

                try {
                    const response = await fetch(`/admin/jadwal-imunisasi/${jadwalId}/panggil-wa`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        showNotification(result.message || 'Notifikasi WhatsApp berhasil dikirim!', 'success');
                    } else {
                        showNotification(result.message || 'Gagal mengirim notifikasi.', 'danger');
                    }
                } catch (error) {
                    showNotification('Terjadi kesalahan jaringan saat mengirim notifikasi.', 'danger');
                } finally {
                    this.disabled = false;
                    this.textContent = 'Panggil';
                }
            });
        });
    });
</script>
@endpush
@endsection
