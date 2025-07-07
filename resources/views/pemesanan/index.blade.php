@extends('layouts.app')

@section('title', 'Daftar Pemesanan Saya')

@section('content')

<div class="container-fluid px-0">
    <!-- Hero Header -->
    <div class="order-hero-bg">
        
        <div class="container py-8">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold text-white mb-3">Riwayat Pemesanan</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pemesanan</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('beranda') }}" class="btn btn-outline-light rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-6">
        @if($pemesanans->isEmpty())
        <!-- Empty State -->
        <div class="empty-state-card">
            <div class="empty-state-content">
                <img src="{{ asset('images/illustrations/no-orders.svg') }}" alt="No orders" class="empty-state-img">
                <h3 class="empty-state-title">Belum Ada Pemesanan</h3>
                <p class="empty-state-text">Mulai jelajahi gedung-gedung terbaik untuk acara Anda</p>
                <a href="{{ route('beranda') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-search me-2"></i> Cari Gedung
                </a>
            </div>
        </div>
        @else
        <!-- Order Dashboard -->
        <div class="order-dashboard mb-5">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card primary">
                        <div class="stats-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-info">
                            <h5>Menunggu</h5>
                            <p>{{ $statusCounts['menunggu_pembayaran'] ?? 0 }} Pemesanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card success">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h5>Dikonfirmasi</h5>
                            <p>{{ $statusCounts['dikonfirmasi'] ?? 0 }} Pemesanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card completed">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stats-info">
                            <h5>Selesai</h5>
                            <p>{{ $statusCounts['selesai'] ?? 0 }} Pemesanan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card danger">
                        <div class="stats-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stats-info">
                            <h5>Dibatalkan</h5>
                            <p>{{ $statusCounts['dibatalkan'] ?? 0 }} Pemesanan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order List -->
        <div class="order-list-card">
            <div class="order-list-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <h4 class="mb-1">Daftar Pemesanan</h4>
                        <p class="text-muted mb-0">Total {{ $pemesanans->total() }} pemesanan</p>
                    </div>
                    <div class="d-flex">
                        <div class="search-box me-3">
                            <i class="fas fa-search"></i>
                            <input type="text" id="search-orders" placeholder="Cari pemesanan...">
                        </div>
                        <select id="status-filter" class="form-select form-select-sm w-auto">
                            <option value="all">Semua Status</option>
                            <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                            <option value="dibayar">Dibayar</option>
                            <option value="dikonfirmasi">Dikonfirmasi</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="order-list-body">
                @foreach($pemesanans as $pemesanan)
                <div class="order-item" data-status="{{ $pemesanan->status }}" data-id="{{ $pemesanan->id_pemesanan }}">
                    <div class="order-item-header">
                        <div class="order-id">
                            <span class="badge bg-light text-dark me-2">#{{ substr($pemesanan->id_pemesanan, 0, 8) }}</span>
                            <span class="order-date">{{ $pemesanan->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-{{ $pemesanan->status }}">
                                {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-item-body">
                        <div class="order-venue">
                            <div class="venue-image">
                                <img src="{{ asset('storage/' . $pemesanan->gedung->gambar) }}" 
                                     alt="{{ $pemesanan->gedung->nama }}" 
                                     class="img-fluid rounded-3">
                            </div>
                            <div class="venue-info">
                                <h5 class="venue-name">{{ $pemesanan->gedung->nama }}</h5>
                                <p class="venue-location">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $pemesanan->gedung->lokasi }}
                                </p>
                                <div class="venue-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        {{ $pemesanan->tanggal_mulai->format('d M Y') }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $pemesanan->tanggal_mulai->format('H:i') }} - {{ $pemesanan->tanggal_selesai->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-summary">
                            <div class="summary-item">
                                <span class="summary-label">Total Pembayaran</span>
                                <span class="summary-value">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-actions">
                                <a href="{{ route('pemesanan.show', $pemesanan->id_pemesanan) }}" 
                                   class="btn btn-outline-secondary btn-sm rounded-pill">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                                @if($pemesanan->status === 'menunggu_pembayaran')
                                <a href="{{ route('pembayaran.show', $pemesanan->id_pemesanan) }}" 
                                   class="btn btn-primary btn-sm rounded-pill">
                                    <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="order-list-footer">
                <nav aria-label="Page navigation">
                    {{ $pemesanans->onEachSide(1)->links('vendor.pagination.custom') }}
                </nav>
            </div>
        </div>
        @endif
        
    </div>
</div>
@endsection

@push('styles')
<style>
/* Custom CSS for premium design */
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --warning-color: #f8961e;
    --info-color: #4895ef;
    --dark-color: #212529;
    --light-color: #f8f9fa;
}

/* Hero Section */
.order-hero-bg {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    padding: 5rem 0;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.order-hero-bg::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
    z-index: -1;
}

.breadcrumb-dark .breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s;
}

.breadcrumb-dark .breadcrumb-item a:hover {
    color: white;
    text-decoration: underline;
}

.breadcrumb-dark .breadcrumb-item.active {
    color: white;
    font-weight: 500;
}

.breadcrumb-dark .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.5);
}

/* Empty State */
.empty-state-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    padding: 3rem;
    text-align: center;
    max-width: 600px;
    margin: 2rem auto;
    transition: transform 0.3s, box-shadow 0.3s;
}

.empty-state-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.empty-state-img {
    height: 180px;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.empty-state-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
    font-size: 1rem;
}

/* Stats Dashboard */
.stats-card {
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    transition: all 0.3s;
    height: 100%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stats-card.primary {
    background-color: rgba(67, 97, 238, 0.1);
    border-left: 4px solid var(--primary-color);
}

.stats-card.success {
    background-color: rgba(76, 201, 240, 0.1);
    border-left: 4px solid var(--success-color);
}

.stats-card.completed {
    background-color: rgba(56, 176, 0, 0.1);
    border-left: 4px solid #38b000;
}

.stats-card.danger {
    background-color: rgba(247, 37, 133, 0.1);
    border-left: 4px solid var(--danger-color);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.25rem;
}

.stats-card.primary .stats-icon {
    background-color: rgba(67, 97, 238, 0.2);
    color: var(--primary-color);
}

.stats-card.success .stats-icon {
    background-color: rgba(76, 201, 240, 0.2);
    color: var(--success-color);
}

.stats-card.completed .stats-icon {
    background-color: rgba(56, 176, 0, 0.2);
    color: #38b000;
}

.stats-card.danger .stats-icon {
    background-color: rgba(247, 37, 133, 0.2);
    color: var(--danger-color);
}

.stats-info h5 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--dark-color);
}

.stats-info p {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0;
    color: var(--dark-color);
}

/* Order List Card */
.order-list-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.order-list-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f1f1f1;
}

.order-list-body {
    padding: 0;
}

.order-item {
    padding: 1.5rem;
    border-bottom: 1px solid #f1f1f1;
    transition: all 0.3s;
}

.order-item:hover {
    background-color: #f9f9f9;
}

.order-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.order-id {
    display: flex;
    align-items: center;
}

.order-id .badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.6rem;
    border-radius: 6px;
    font-weight: 600;
}

.order-date {
    font-size: 0.85rem;
    color: #6c757d;
}

.order-status .status-badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-weight: 600;
}

.status-menunggu_pembayaran {
    background-color: #fff3cd;
    color: #856404;
}

.status-dibayar {
    background-color: #cce5ff;
    color: #004085;
}

.status-dikonfirmasi {
    background-color: #d4edda;
    color: #155724;
}

.status-selesai {
    background-color: #e2e3e5;
    color: #383d41;
}

.status-dibatalkan {
    background-color: #f8d7da;
    color: #721c24;
}

.order-item-body {
    display: flex;
    flex-direction: column;
}

.order-venue {
    display: flex;
    margin-bottom: 1.5rem;
}

.venue-image {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.venue-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.venue-info {
    flex-grow: 1;
}

.venue-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.venue-location {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.75rem;
}

.venue-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.meta-item {
    font-size: 0.85rem;
    color: #495057;
    display: flex;
    align-items: center;
}

.order-summary {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.summary-item {
    display: flex;
    flex-direction: column;
}

.summary-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.summary-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark-color);
}

.summary-actions {
    display: flex;
    gap: 0.75rem;
}

.order-list-footer {
    padding: 1.5rem;
    border-top: 1px solid #f1f1f1;
    display: flex;
    justify-content: center;
}

/* Search Box */
.search-box {
    position: relative;
    width: 200px;
}

.search-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #adb5bd;
}

.search-box input {
    width: 100%;
    padding: 0.375rem 0.75rem 0.375rem 2.25rem;
    font-size: 0.875rem;
    border-radius: 50px;
    border: 1px solid #dee2e6;
    transition: all 0.3s;
}

.search-box input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    outline: none;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .order-hero-bg {
        padding: 3rem 0;
    }
    
    .order-item-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .order-venue {
        flex-direction: column;
    }
    
    .venue-image {
        width: 100%;
        height: 180px;
        margin-right: 0;
        margin-bottom: 1rem;
    }
    
    .order-summary {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .search-box {
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .order-list-header .d-flex {
        flex-direction: column;
        width: 100%;
    }
    
    .order-list-header .form-select {
        width: 100%;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.order-item {
    animation: fadeIn 0.5s ease-out forwards;
    opacity: 0;
}

.order-item:nth-child(1) { animation-delay: 0.1s; }
.order-item:nth-child(2) { animation-delay: 0.2s; }
.order-item:nth-child(3) { animation-delay: 0.3s; }
.order-item:nth-child(4) { animation-delay: 0.4s; }
.order-item:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter functionality
    const statusFilter = document.getElementById('status-filter');
    const orderItems = document.querySelectorAll('.order-item');
    const searchInput = document.getElementById('search-orders');
    
    // Set initial filter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');
    if (statusParam) {
        statusFilter.value = statusParam;
        filterOrders(statusParam);
    }
    
    // Filter change event
    statusFilter.addEventListener('change', function() {
        const status = this.value;
        const url = new URL(window.location.href);
        
        if (status === 'all') {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', status);
        }
        
        window.history.pushState({}, '', url.toString());
        filterOrders(status);
    });
    
    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const status = statusFilter.value;
        
        orderItems.forEach(item => {
            const id = item.getAttribute('data-id').toLowerCase();
            const venueName = item.querySelector('.venue-name').textContent.toLowerCase();
            const matchesSearch = id.includes(searchTerm) || venueName.includes(searchTerm);
            const matchesStatus = status === 'all' || item.getAttribute('data-status') === status;
            
            if (matchesSearch && matchesStatus) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Filter orders by status
    function filterOrders(status) {
        orderItems.forEach(item => {
            if (status === 'all' || item.getAttribute('data-status') === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Add hover effects to stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush