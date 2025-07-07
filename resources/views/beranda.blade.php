<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Venuefy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style-beranda.css">

</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-building"></i>
                <span>Venuefy</span>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="{{ route('beranda') }}" class="nav-link active">
                        <i class="fas fa-home"></i>
                        <span>Beranda</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('pemesanan.index') }}" class="nav-link">
                        <i class="fas fa-calendar-check"></i>
                        <span>Pemesanan</span>
                    </a>
                </div>
                @auth
                <div class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="nav-link">
                        @csrf
                        <i class="fas fa-sign-out-alt"></i>
                        <button type="submit" class="bg-transparent border-0 text-white p-0">Logout</button>
                    </form>
                </div>
                @else
                <div class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </div>
                @endauth
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container-fluid">
                <!-- Search Bar -->
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <form id="searchForm" method="GET" action="{{ route('beranda') }}">
                        <input type="text" name="search" placeholder="Cari gedung..." 
                               class="search-input" value="{{ request('search') }}">
                    </form>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button id="filterBtn" class="action-btn">
                        <i class="fas fa-sliders-h"></i>
                        <span>Filter</span>
                    </button>
                    <button id="calendarBtn" class="action-btn">
                        <i class="far fa-calendar-alt"></i>
                        <span>Kalender</span>
                    </button>
                </div>
                
                <!-- Building List -->
                <div class="building-list p-4" style="background: rgb(233, 233, 233); border-radius: 20px;">
                    @forelse ($gedungs as $gedung)
                    <div class="building-card fade-in slide-up" onclick="detail('{{ $gedung->id_gedung }}')">
                        <div class="row g-0">
                            <div class="col-md-4">
                               <div class="image-container">
                                    @if($gedung->first_image)
                                        <img src="{{ asset('storage/gedung_images/' . $gedung->first_image) }}" 
                                            class="building-image" 
                                            alt="{{ $gedung->nama }}"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-fallback\'><i class=\'fas fa-image\'></i></div>'">
                                    @else
                                        <div class='image-fallback'><i class='fas fa-image'></i></div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="building-content">
                                    <div class="building-header">
                                        <div>
                                            <h3 class="building-title">{{ $gedung->nama }}</h3>
                                            <div class="building-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $gedung->lokasi }} • {{ ucfirst($gedung->daerah) }}</span>
                                            </div>
                                        </div>
                                        <span class="building-category">{{ $gedung->kategori->nama_kategori }}</span>
                                    </div>
                                    
                                    <p class="building-description">{{ $gedung->deskripsi }}</p>
                                    
                                    <div class="building-features">
                                        @foreach(explode(',', $gedung->fasilitas) as $item)
                                        <span class="feature-tag">{{ trim($item) }}</span>
                                        @endforeach
                                    </div>
                                    
                                    <div class="building-footer">
                                        <div class="building-price">
                                            Rp {{ number_format($gedung->harga, 0, ',', '.') }} / Jam
                                        </div>
                                        <div class="building-capacity">
                                            <i class="fas fa-users"></i>
                                            <span>{{ $gedung->kapasitas }} orang</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="far fa-building"></i>
                        <h3>Tidak Ada Gedung Ditemukan</h3>
                        <p>Maaf, tidak ada gedung yang sesuai dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('beranda') }}" class="btn btn-primary">Reset Filter</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    
    <!-- Filter Modal -->
    <div id="filterModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Filter Ruangan</h3>
                <button class="modal-close" id="closeFilterModal">&times;</button>
            </div>
            <form id="filterForm" method="GET" action="{{ route('beranda') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Daerah</label>
                        <select name="daerah" class="form-control">
                            <option value="">Semua Daerah</option>
                            <option value="kota bandung utara">Kota Bandung Utara</option>
                            <option value="kota bandung barat">Kota Bandung Barat</option>
                            <option value="kota bandung selatan">Kota Bandung Selatan</option>
                            <option value="kota bandung timur">Kota Bandung Timur</option>
                            <option value="kabupaten bandung barat">Kab. Bandung Barat</option>
                            <option value="kabupaten bandung">Kab. Bandung</option>
                            <option value="kota cimahi">Kota Cimahi</option>
                            <option value="kabupaten sumedang">Kab. Sumedang</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kapasitas Minimum</label>
                        <input type="number" name="kapasitas" min="1" class="form-control" 
                               placeholder="Masukkan jumlah orang">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="resetFilter" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Calendar Modal (Placeholder) -->
    <div id="calendarModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Kalender Ketersediaan</h3>
                <button class="modal-close" id="closeCalendarModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Fitur kalender akan menampilkan ketersediaan gedung berdasarkan tanggal.</p>
                <!-- Calendar implementation would go here -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="closeCalendarBtn">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="./js/script-beranda.js"></script>
</body>
</html>