<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Monitoring Imunisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #1e293b;
            color: #f8fafc;
        }
        .sidebar {
            height: 100vh;
            background-color: #111827;
            color: #f8fafc;
            padding-top: 20px;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar .brand-link {
            padding: 16px;
            text-align: center;
            margin-bottom: 20px;
            display: block;
            color: #f8fafc;
            text-decoration: none;
            font-size: 2rem;
        }
        .sidebar .nav-item .nav-link {
            padding: 12px 16px;
            color: #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-item .nav-link i {
            margin-right: 8px;
            color: #94a3b8;
        }
        .sidebar .nav-item .nav-link span {
            margin-left: 8px;
        }
        .sidebar .nav-item .nav-link:hover {
            background-color: #1f2937;
            color: #f8fafc;
        }
        .sidebar .logout-section {
            padding: 16px;
            border-top: 1px solid #334155;
        }
        .sidebar .logout-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            width: 100%;
            text-align: center;
        }
        .sidebar .logout-button:hover {
            background-color: #c82333;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            background-color: #1c2536;
            color: #f8fafc;
            border: 1px solid #334155;
        }
        .card-header {
            background-color: #171f2b;
            border-bottom: 1px solid #334155;
        }
        .nav-link {
            color: #cbd5e1 !important;
        }
        .nav-link:hover {
            color: #f8fafc !important;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="sidebar p-3 d-flex flex-column justify-content-between">
        <div>
            <a href="{{ route('user.dashboard') }}" class="brand-link">
                <i class="fas fa-syringe"></i>
            </a>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('user.jadwal-imunisasi.index') ? 'active' : '' }}" href="{{ route('user.jadwal-imunisasi.index') }}">
                        <i class="fas fa-calendar-alt"></i><span class="ms-2">Jadwal Imunisasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('user.riwayat-imunisasi.index') ? 'active' : '' }}" href="{{ route('user.riwayat-imunisasi.index') }}">
                        <i class="fas fa-file-alt"></i><span class="ms-2">Riwayat Imunisasi</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="logout-section">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content container-fluid p-4">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
