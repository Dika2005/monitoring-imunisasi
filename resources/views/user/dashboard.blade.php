@extends('layouts.app') {{-- Sesuaikan dengan layout kamu --}}
@section('page_title', 'Dashboard User')

@section('content')
<div class="container mt-4">
    <h2 class="text-white mb-4">Ringkasan Imunisasi</h2>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Sudah Imunisasi</h5>
                    <p class="card-text fs-3">{{ $sudah }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Akan Datang</h5>
                    <p class="card-text fs-3">{{ $akanDatang }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Belum Imunisasi</h5>
                    <p class="card-text fs-3">{{ $belum }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
