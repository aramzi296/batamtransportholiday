<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Member Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Dark Green Theme Override */
        :root {
            --bs-primary: #1e7e34;
            --bs-primary-rgb: 30, 126, 52;
            --bs-primary-dark: #155724;
        }
        
        .bg-primary, .btn-primary {
            background-color: #1e7e34 !important;
            border-color: #1e7e34 !important;
        }
        
        .bg-primary:hover, .btn-primary:hover {
            background-color: #1a6e2d !important;
            border-color: #1a6e2d !important;
        }
        
        .text-primary {
            color: #1e7e34 !important;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #155724;
            z-index: 1000;
            transition: transform 0.3s;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        .sidebar-brand {
            padding: 1rem;
            background-color: #1e7e34;
            color: white;
            text-align: center;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li a {
            display: block;
            padding: 0.75rem 1rem;
            color: #c8e6c9;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: background-color 0.3s;
        }
        .sidebar-nav li a:hover,
        .sidebar-nav li a.active {
            background-color: #1e7e34;
            color: white;
        }
        .content-wrapper {
            min-height: calc(100vh - 56px);
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h5><i class="fas fa-users"></i> Member Panel</h5>
            <small>{{ Auth::user()->name }}</small>
        </div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('member.dashboard') }}" class="{{ request()->is('member/dasbor') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('member.profile.index') }}" class="{{ request()->is('member/profile*') ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i> Profil Anggota
                </a>
            </li>
            <li>
                <a href="{{ route('member.vehicles.index') }}" class="{{ request()->is('member/vehicles*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> Daftar Kendaraan
                </a>
            </li>
            <li>
                <a href="{{ route('member.off-days.index') }}" class="{{ request()->is('member/off-days*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i> Hari Off
                </a>
            </li>
            <li>
                <a href="{{ route('member.members.index') }}" class="{{ request()->is('member/members*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Daftar Member
                </a>
            </li>
            <li>
                <a href="{{ route('member.account.index') }}" class="{{ request()->is('member/account*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Manajemen Akun
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </li>
            <li>
                <form action="{{ route('member.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-start w-100 text-decoration-none" style="color: white; padding: 0.75rem 1rem; border: none; border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1e7e34;">
            <div class="container-fluid">
                <button class="navbar-toggler d-lg-none" type="button" onclick="toggleSidebar()">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span class="navbar-brand mb-0 h1">@yield('page-title', 'Dashboard Member')</span>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text text-white">
                        <i class="fas fa-user"></i> {{ Auth::user()->name }}
                    </span>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
    
    @stack('scripts')
</body>
</html>

