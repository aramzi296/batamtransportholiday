<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Admin LTE CSS -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        .sidebar-brand {
            padding: 1rem;
            background-color: #495057;
            color: white;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li a {
            display: block;
            padding: 0.75rem 1rem;
            color: #adb5bd;
            text-decoration: none;
            border-bottom: 1px solid #495057;
        }
        .sidebar-nav li a:hover,
        .sidebar-nav li a.active {
            background-color: #495057;
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
            <h4><i class="fas fa-tachometer-alt"></i> Admin Panel</h4>
        </div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ url('/admin') }}" class="{{ request()->is('admin') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/vehicles') }}" class="{{ request()->is('admin/vehicles*') ? 'active' : '' }}">
                    <i class="fas fa-car"></i> Kendaraan
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/bookings') }}" class="{{ request()->is('admin/bookings*') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i> Booking
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/articles') }}" class="{{ request()->is('admin/articles*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Artikel
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/article-categories') }}" class="{{ request()->is('admin/article-categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Kategori Artikel
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/vehicle-categories') }}" class="{{ request()->is('admin/vehicle-categories*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i> Kategori Kendaraan
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/availability') }}" class="{{ request()->is('admin/availability*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Ketersediaan
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/testimonials') }}" class="{{ request()->is('admin/testimonials*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Testimoni
                </a>
            </li>
            <li>
                <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <i class="fas fa-globe"></i> Lihat Website
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-start w-100 text-decoration-none" style="color: #adb5bd; padding: 0.75rem 1rem; border: none; border-bottom: 1px solid #495057;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <button class="navbar-toggler d-lg-none" type="button" onclick="toggleSidebar()">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span class="navbar-brand mb-0 h1">@yield('page-title')</span>
                <div class="navbar-nav ms-auto">
                    <span class="navbar-text">
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