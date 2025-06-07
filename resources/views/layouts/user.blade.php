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
            background-color: #343a40; /* Dark background as in admin dashboard */
            color: #f8f9fa;
            display: flex; /* Use flexbox for sidebar and content layout */
            min-height: 100vh; /* Ensure full viewport height */
            margin: 0;
        }
        .sidebar {
            width: 250px; /* Fixed width for sidebar */
            background-color: #212529; /* Darker background for sidebar */
            padding-top: 20px;
            flex-shrink: 0; /* Prevent sidebar from shrinking */
            /* --- Ini adalah properti yang mendorong logout ke bawah --- */
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Mendorong item pertama ke atas, item terakhir ke bawah */
            height: 100vh; /* Memastikan sidebar mengambil tinggi penuh viewport */
            /* ----------------------------------------------- */
        }
        .sidebar .sidebar-header {
            color: #f8f9fa;
            text-align: center;
            padding-bottom: 20px;
            font-size: 1.5rem;
            border-bottom: 1px solid #495057; /* Separator */
            margin-bottom: 20px;
        }
        .sidebar .sidebar-header img {
            max-width: 150px; /* Adjust logo size as needed */
            height: auto;
            display: block; /* Ensure image takes up full width */
            margin: 0 auto; /* Center the logo */
        }
        /* Mengubah selektor untuk ul.list-unstyled karena sekarang dibungkus div */
        .sidebar .top-content ul.list-unstyled {
            padding-left: 0;
            list-style: none;
        }
        .sidebar ul.list-unstyled li a {
            display: block;
            padding: 10px 20px;
            color: #adb5bd; /* Lighter text for links */
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidebar ul.list-unstyled li a:hover,
        .sidebar ul.list-unstyled li a.active {
            background-color: #007bff; /* Highlight on hover/active */
            color: #ffffff;
        }
        .sidebar ul.list-unstyled li a i {
            margin-right: 10px; /* Space between icon and text */
        }
        .content {
            flex-grow: 1; /* Content takes remaining space */
            padding: 20px;
            background-color: #343a40; /* Same as body background */
        }
        .card {
            background-color: #212529; /* Card background */
            color: #f8f9fa;
            border: 1px solid rgba(255, 255, 255, 0.125);
        }
        .card-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.125);
        }
        /* Custom styling for the logout form/button */
        .logout-form {
            padding: 16px 20px; /* Menyesuaikan padding agar mirip dengan admin (16px atas/bawah, 20px samping) */
            border-top: 1px solid #334155; /* Menambahkan garis atas seperti admin */
            /* margin-top tidak lagi diperlukan karena padding atas sudah diatur */
        }
        .logout-form button {
            background-color: #dc3545; /* Red for logout button */
            color: white;
            border: none;
            width: 100%;
            text-align: center; /* Mengubah perataan teks menjadi tengah seperti admin */
            padding: 8px 15px; /* Menyesuaikan padding tombol seperti admin */
            border-radius: 5px; /* Menambahkan border-radius seperti admin */
            font-size: 0.9rem; /* Menyesuaikan ukuran font seperti admin */
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
            margin-right: 8px; /* Menyesuaikan margin icon seperti admin */
        }
    </style>
</head>
<body>

    <div class="sidebar">
        {{-- Pembungkus baru untuk sidebar-header dan navigasi menu --}}
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
                    {{-- PERUBAHAN DI SINI: Link ke rute laporan user --}}
                    <a href="{{ route('user.laporan.index') }}" class="{{ Request::routeIs('user.laporan.index') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i> Laporan Imunisasi
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
    {{-- Pastikan @stack('scripts') ada di sini jika Anda menggunakannya --}}
    @stack('scripts')
</body>
</html>