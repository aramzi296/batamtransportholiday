<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - D'Sarana</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        /* Dark Green Theme Override */
        :root {
            --bs-primary: #1e7e34;
            --bs-primary-rgb: 30, 126, 52;
            --bs-primary-dark: #155724;
            --bs-primary-light: #28a745;
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
        
        .border-primary {
            border-color: #1e7e34 !important;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            min-height: 500px;
            display: flex;
            align-items: center;
        }
        .vehicle-card {
            transition: transform 0.3s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .navbar-dark.bg-dark {
            background-color: #155724 !important;
        }
        .footer {
            background-color: #155724;
            color: white;
            padding: 2rem 0;
        }
        /* Ensure navbar stays above sticky elements */
        .navbar.sticky-top {
            z-index: 1030;
        }
        /* Adjust sticky cards to respect navbar */
        .sticky-top:not(.navbar) {
            top: 70px; /* Default offset for sticky elements */
        }
        
        /* Responsive sticky behavior */
        @media (max-width: 991.98px) {
            .sticky-top:not(.navbar) {
                position: relative !important;
                top: 0 !important;
            }
        }
        
        /* Smooth transitions for sticky elements */
        .sticky-top:not(.navbar) {
            transition: top 0.2s ease-in-out;
        }
    </style>
    
    @livewireStyles
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #155724;">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-car"></i> D'Sarana
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/vehicles') }}">
                            <i class="fas fa-car"></i> Armada
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="{{ url('/prices') }}">
                            <i class="fas fa-tags"></i> Harga
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/articles') }}">
                            <i class="fas fa-newspaper"></i> Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('faq.index') }}">
                            <i class="fas fa-question-circle"></i> FAQ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">
                            <i class="fas fa-phone"></i> Kontak
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i> Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->isAdmin() || Auth::user()->isMember())
                                    @if(Auth::user()->isAdmin())
                                        <li><a class="dropdown-item" href="{{ url('/admin') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dasbor
                                        </a></li>
                                    @elseif(Auth::user()->isMember())
                                        <li><a class="dropdown-item" href="{{ route('member.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i> Dasbor
                                        </a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li><a class="dropdown-item" href="{{ url('/profile') }}">
                                        <i class="fas fa-user-circle"></i> Profil
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ url('/bookings') }}">
                                        <i class="fas fa-calendar"></i> Booking Saya
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-car"></i> D'Sarana</h5>
                    <p>Layanan rental mobil dan bus terpercaya dengan kualitas terbaik dan harga terjangkau.</p>
                </div>
                <div class="col-md-4">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-white text-decoration-none"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="{{ url('/vehicles') }}" class="text-white text-decoration-none"><i class="fas fa-car"></i> Armada</a></li>
                        <li><a href="{{ url('/prices') }}" class="text-white text-decoration-none"><i class="fas fa-tags"></i> Daftar Harga</a></li>
                        <li><a href="{{ url('/contact') }}" class="text-white text-decoration-none"><i class="fas fa-phone"></i> Kontak</a></li>
                        <!-- <li><a href="{{ route('member.login') }}" class="text-white text-decoration-none"><i class="fas fa-users"></i> Member</a></li> -->
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p><i class="fas fa-phone"></i> +62 821 7086 0825</p>
                    <p><i class="fas fa-envelope"></i> info@dsarana.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Mall Top 100 Tembesi Blok H3 No. 1, Batam, Indonesia</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} D'Sarana. All rights reserved.</p>
                <p>
                    <a href="{{ route('terms') }}" class="text-white text-decoration-underline">Syarat dan Ketentuan</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Fix sticky elements position -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate navbar height and adjust sticky elements
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                const navbarHeight = navbar.offsetHeight;
                const stickyElements = document.querySelectorAll('.sticky-top:not(.navbar)');
                
                stickyElements.forEach(function(element) {
                    element.style.top = (navbarHeight + 20) + 'px'; // navbar height + 20px margin
                });
            }
        });
        
        // Recalculate on window resize
        window.addEventListener('resize', function() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                const navbarHeight = navbar.offsetHeight;
                const stickyElements = document.querySelectorAll('.sticky-top:not(.navbar)');
                
                stickyElements.forEach(function(element) {
                    element.style.top = (navbarHeight + 20) + 'px';
                });
            }
        });
    </script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>