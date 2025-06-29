<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title') - Aplikasi Imunisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #343a40;
            color: #f8f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #212529;
            padding-top: 20px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
        }
        .sidebar .sidebar-header {
            color: #f8f9fa;
            text-align: center;
            padding-bottom: 20px;
            font-size: 1.5rem;
            border-bottom: 1px solid #495057;
            margin-bottom: 20px;
        }
        .sidebar .sidebar-header img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .sidebar .top-content ul.list-unstyled {
            padding-left: 0;
            list-style: none;
        }
        .sidebar ul.list-unstyled li a {
            display: block;
            padding: 10px 20px;
            color: #adb5bd;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar ul.list-unstyled li a:hover,
        .sidebar ul.list-unstyled li a.active {
            background-color: #007bff;
            color: #ffffff;
        }
        .sidebar ul.list-unstyled li a i {
            margin-right: 10px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #343a40;
        }
        .card {
            background-color: #212529;
            color: #f8f9fa;
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.125);
        }
        .logout-form {
            padding: 16px 20px;
            border-top: 1px solid #334155;
        }
        .logout-form button {
            background-color: #dc3545;
            color: white;
            border: none;
            width: 100%;
            text-align: center;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            text-decoration: none;
            display: block;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .logout-form button:hover {
            background-color: #c82333;
            color: white;
        }
        .logout-form button i {
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="top-content">
            <div class="sidebar-header">
                <img src="{{ asset('images/imunisasi.png') }}" alt="Logo Aplikasi Imunisasi">
            </div>
            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('user.dashboard') }}" class="{{ Request::routeIs('user.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.jadwal-imunisasi.index') }}" class="{{ Request::routeIs('user.jadwal-imunisasi.index') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Jadwal Imunisasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.riwayat.index') }}" class="{{ Request::routeIs('user.riwayat.index') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Riwayat Imunisasi
                    </a>
                </li>
            </ul>
        </div>
        <div class="logout-form">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
