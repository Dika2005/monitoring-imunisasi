@extends('layouts.admin')

@section('content')
    <div class="container mt-4"> {{-- Tambahkan container untuk konsistensi layout --}}
        <h2>Data Jadwal Imunisasi</h2>
        <a href="{{ route('admin.jadwal-imunisasi.create') }}" class="btn btn-primary mb-3">Tambah Jadwal Imunisasi</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Container untuk pesan notifikasi --}}
        <div id="notificationContainer" class="alert alert-info mt-3 d-none"></div>

        <div class="table-responsive">
            {{-- PERUBAHAN DI SINI: Menambahkan kelas Bootstrap untuk styling dark table --}}
            <table class="table table-dark table-striped table-hover table-bordered">
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
                            {{-- Memformat tanggal agar lebih mudah dibaca --}}
                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_imunisasi)->translatedFormat('d F Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.jadwal-imunisasi.edit', $jadwal->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('admin.jadwal-imunisasi.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            Hapus
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.jadwal-imunisasi.selesai', $jadwal->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT') {{-- Ini perbaikan penting, sudah ada sebelumnya --}}
                                        <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                    </form>

                                    {{-- Tombol Panggil --}}
                                    <button
                                        type="button"
                                        class="btn btn-info btn-sm call-button"
                                        data-id="{{ $jadwal->id }}"
                                        data-nama-balita="{{ $jadwal->balita->nama }}"
                                        data-jenis-vaksin="{{ $jadwal->jenis_vaksin }}"
                                        data-tanggal-imunisasi="{{ $jadwal->tanggal_imunisasi }}"
                                        data-user-email="{{ $jadwal->balita->user->email ?? '' }}"
                                    >
                                        Panggil
                                    </button>
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
    </div> {{-- Penutup div container --}}

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
                    const namaBalita = this.dataset.namaBalita;
                    const jenisVaksin = this.dataset.jenisVaksin;
                    const tanggalImunisasi = this.dataset.tanggalImunisasi;
                    const userEmail = this.dataset.userEmail;

                    // Mengubah tombol menjadi disabled dan menampilkan "Memanggil..."
                    this.disabled = true;
                    this.textContent = 'Memanggil...';
                    showNotification('Mengirim notifikasi email, mohon tunggu...', 'info');

                    // Validasi email
                    if (!userEmail) {
                        console.error('Email pengguna tidak ditemukan untuk balita ini.');
                        showNotification('Gagal: Email orang tua balita tidak ditemukan.', 'danger');
                        this.disabled = false;
                        this.textContent = 'Panggil';
                        return;
                    }

                    try {
                        const response = await fetch(`/admin/jadwal-imunisasi/${jadwalId}/panggil`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                nama_balita: namaBalita,
                                jenis_vaksin: jenisVaksin,
                                tanggal_imunisasi: tanggalImunisasi,
                                user_email: userEmail
                            })
                        });

                        const result = await response.json();

                        if (response.ok) {
                            showNotification(`Notifikasi email berhasil dikirim ke ${userEmail}.`, 'success');
                        } else {
                            console.error(`Gagal mengirim email: ${result.message || 'Terjadi kesalahan.'}`, result);
                            showNotification(`Gagal mengirim notifikasi email: ${result.message || 'Terjadi kesalahan.'}`, 'danger');
                        }
                    } catch (error) {
                        console.error('Error saat mengirim permintaan: ', error);
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