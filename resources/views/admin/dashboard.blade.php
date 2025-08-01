@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white">Dashboard</h1>
    </div>

    <!-- Statistik Cards -->
    <div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 bg-dark text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Total Balita Terdaftar</div>
                    <div class="h5 mb-0 font-weight-bold text-white">{{ $totalBalita }}</div>
                </div>
                <div class="icon">
                    <i class="bi bi-people-fill fs-1 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 bg-dark text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Jadwal Imunisasi Bulan Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-white">{{ $totalJadwalBulanIni }}</div>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar-check-fill fs-1 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 bg-dark text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Riwayat</div>
                    <div class="h5 mb-0 font-weight-bold text-white">{{ $totalRiwayat }}</div>
                </div>
                <div class="icon">
                    <i class="bi bi-clipboard-data-fill fs-1 text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-header">Grafik Riwayat Imunisasi per Bulan</div>
                <div class="card-body">
                    <div style="position: relative; height:300px;">
                        <canvas id="chartPerBulan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white shadow">
                <div class="card-header">Grafik Status Imunisasi</div>
                <div class="card-body">
                    <div style="position: relative; height:300px;">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctxBulan = document.getElementById('chartPerBulan').getContext('2d');
    new Chart(ctxBulan, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulanLabels) !!},
            datasets: [{
                label: 'Jumlah Riwayat',
                data: {!! json_encode($jumlahPerBulan) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: 'white' },
                    grid: { color: '#444' }
                },
                x: {
                    ticks: { color: 'white' },
                    grid: { color: '#444' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: 'white' }
                }
            }
        }
    });

    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Belum Imunisasi', 'Terlambat'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    {{ $statusCounts['selesai'] ?? 0 }},
                    {{ $statusCounts['belum imunisasi'] ?? 0 }},
                    {{ $statusCounts['terlambat'] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { color: 'white' }
                }
            }
        }
    });
});
</script>
@endpush
