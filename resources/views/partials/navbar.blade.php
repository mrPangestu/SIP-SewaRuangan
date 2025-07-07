<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ url('css/style-navbar.css') }}">

<nav class="navbar-premium navbar-expand-lg fixed-top">
    <div class="container">
        <div class="collapse navbar-collapse nav-menu" id="navbarNav">
            <h3 class="navbar-brand brand-logo" href="#">Venuefy</h3>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-2">
                    <a href="{{ route('beranda') }}" 
                    class="nav-link nav-link-item {{ Route::currentRouteName() == 'beranda' ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a href="{{ route('pemesanan.index') }}" 
                    class="nav-link nav-link-item {{ Route::currentRouteName() == 'pemesanan.index' ? 'active' : '' }}">
                        Pesanan
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a href="#" class="nav-link nav-link-item">
                        Fitur
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a href="#" class="nav-link nav-link-item">
                        Kontak
                    </a>
                </li>
                
            </ul>
            
            <div class="auth-buttons d-flex align-items-center ms-lg-4">
                @auth
                    <a href="#" class="profile-btn-wrapper ms-3 position-relative">
                        <div class="profile-btn">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="profile-tooltip">{{ auth()->user()->name }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="auth-btn login-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="auth-btn btn btn-primary ms-3" style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border: none;">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<div style="height: 80px; padding-top: 50px;"></div>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ url('js/script-navbar.js') }}"></script>