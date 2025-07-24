@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card bg-dark text-white p-4 shadow">
            <h3 class="text-center mb-4">Register</h3>

            {{-- ALERT ERROR UMUM --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ALERT ERROR VALIDASI --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="no_telepon" class="form-label">No Telepon</label>
        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" required class="form-control">
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Register</button>
</form>

        </div>
    </div>
</div>
@endsection
