@extends('layouts.app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Header Section -->
    <div class="booking-hero bg-primary-gradient py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-light">
                            <li class="breadcrumb-item"><a href="{{ route('beranda') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('pemesanan.index') }}">Pemesanan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="text-white mb-0">Detail Pemesanan</h1>
                </div>
                <span class="badge booking-status-badge fs-6 py-2 px-3
                    @if($pemesanan->status === 'dibayar') bg-success-light text-success
                    @elseif($pemesanan->status === 'menunggu_pembayaran') bg-warning-light text-warning
                    @elseif($pemesanan->status === 'dibatalkan') bg-danger-light text-danger
                    @else bg-info-light text-info @endif" style="background: rgba(255, 255, 255, 0.94)">
                    {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Main Card -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <!-- Order Summary -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4 mb-0">Ringkasan Pemesanan</h2>
                            <div class="text-muted">ID: {{ $pemesanan->id_pemesanan }}</div>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon bg-primary-light">
                                        <i class="fas fa-calendar-alt text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-title">Tanggal Pemesanan</h6>
                                        <p class="info-value">{{ $pemesanan->created_at->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon bg-info-light">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-title">Nama Penyewa</h6>
                                        <p class="info-value">{{ $pemesanan->user->name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon bg-warning-light">
                                        <i class="fas fa-calendar-check text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-title">Nama Acara</h6>
                                        <p class="info-value">{{ $pemesanan->nama_acara }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon bg-success-light">
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="info-title">Total Pembayaran</h6>
                                        <p class="info-value text-success fw-bold">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Jadwal Acara</h2>
                        <div class="timeline-wrapper">
                            <div class="timeline-item">
                                <div class="timeline-badge bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>Mulai Acara</h6>
                                    <p>{{ $pemesanan->tanggal_mulai->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="timeline-line bg-primary"></div>
                            <div class="timeline-item">
                                <div class="timeline-badge bg-primary"></div>
                                <div class="timeline-content">
                                    <h6>Selesai Acara</h6>
                                    <p>{{ $pemesanan->tanggal_selesai->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="duration-badge mt-3">
                            <i class="fas fa-clock me-2"></i>
                            <span>Durasi: {{ $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai) }} Jam</span>
                        </div>
                    </div>
                </div>

                <!-- Venue Details -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Detail Gedung</h2>
                        <div class="venue-card">
                            <div class="venue-image">
                                 @if($pemesanan->gedung->first_image)
                                    <img src="{{ asset('storage/gedung_images/' . $pemesanan->gedung->first_image) }}" 
                                        class="building-image" 
                                        alt="{{ $pemesanan->gedung->nama }}"
                                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-fallback\'><i class=\'fas fa-image\'></i></div>'">
                                @else
                                    <div class='image-fallback'><i class='fas fa-image'></i></div>
                                @endif
                                <div class="venue-overlay">
                                    <button class="btn btn-sm btn-light rounded-pill" id="viewGallery">
                                        <i class="fas fa-expand me-1"></i> Lihat Galeri
                                    </button>
                                </div>
                            </div>
                            <div class="venue-details mt-3">
                                <h3 class="h5">{{ $pemesanan->gedung->nama }}</h3>
                                <div class="d-flex align-items-center text-muted mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    <span>{{ $pemesanan->gedung->lokasi }}</span>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <div class="spec-card">
                                            <i class="fas fa-users spec-icon bg-primary-light text-primary"></i>
                                            <div>
                                                <small class="text-muted">Kapasitas</small>
                                                <h6 class="mb-0">{{ $pemesanan->gedung->kapasitas }} Orang</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="spec-card">
                                            <i class="fas fa-tag spec-icon bg-success-light text-success"></i>
                                            <div>
                                                <small class="text-muted">Harga per Jam</small>
                                                <h6 class="mb-0">Rp {{ number_format($pemesanan->gedung->harga, 0, ',', '.') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="facilities-section">
                                    <h6 class="mb-2">Fasilitas:</h6>
                                    <div class="facilities-container">
                                        @foreach(explode(',', $pemesanan->gedung->fasilitas) as $fasilitas)
                                        <span class="facility-badge">
                                            <i class="fas fa-check-circle me-1 text-success"></i>
                                            {{ trim($fasilitas) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($pemesanan->pembayaran)
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Informasi Pembayaran</h2>
                        <div class="payment-details">
                            <div class="payment-method">
                                <div class="payment-icon">
                                    @if($pemesanan->pembayaran->metode_pembayaran === 'transfer_bank')
                                    <i class="fas fa-university"></i>
                                    @elseif($pemesanan->pembayaran->metode_pembayaran === 'kartu_kredit')
                                    <i class="far fa-credit-card"></i>
                                    @else
                                    <i class="fas fa-wallet"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6>Metode Pembayaran</h6>
                                    <p>{{ ucfirst(str_replace('_', ' ', $pemesanan->pembayaran->metode_pembayaran)) }}</p>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <h6>Status Pembayaran</h6>
                                        <p class="text-success fw-bold">{{ ucfirst($pemesanan->pembayaran->status) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <h6>Referensi Pembayaran</h6>
                                        <p>{{ $pemesanan->pembayaran->referensi_pembayaran }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <h6>Waktu Pembayaran</h6>
                                        <p>{{ $pemesanan->pembayaran->waktu_pembayaran->format('d F Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($pemesanan->pembayaran->bukti_pembayaran)
                            <div class="payment-proof mt-4">
                                <h6 class="mb-3">Bukti Pembayaran</h6>
                                <div class="proof-image-container">
                                    <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" 
                                        alt="Bukti Pembayaran" class="img-thumbnail proof-image" id="paymentProofImage">
                                    <div class="proof-overlay" id="proofOverlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($pemesanan->status === 'menunggu_pembayaran')
                <div class="card shadow-sm border-warning border-2 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="text-center text-md-start mb-3 mb-md-0">
                                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                    <i class="fas fa-exclamation-circle text-warning me-2 fs-4"></i>
                                    <h5 class="mb-0 text-warning">Menunggu Pembayaran</h5>
                                </div>
                                <p class="mb-0 text-muted">Silakan selesaikan pembayaran untuk mengkonfirmasi pemesanan Anda</p>
                            </div>
                            <a href="{{ route('pembayaran.show', $pemesanan->id_pemesanan) }}" 
                                class="btn btn-warning rounded-pill px-4 py-2 fw-medium">
                                <i class="fas fa-credit-card me-2"></i> Lanjutkan Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar Actions -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px; z-index: 10;">
                    <!-- Action Buttons -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Tindakan</h5>
                            <div class="d-grid gap-3">
                                <a href="{{ route('beranda') }}" 
                                    class="btn btn-outline-secondary rounded-pill py-2">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                                </a>
                                
                                @if($pemesanan->status === 'menunggu_pembayaran')
                                <form action="{{ route('pemesanan.batal', $pemesanan->id_pemesanan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="btn btn-outline-danger rounded-pill py-2 w-100"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                                        <i class="fas fa-times-circle me-2"></i> Batalkan Pemesanan
                                    </button>
                                </form>
                                @endif
                                
                                @if($pemesanan->status === 'dibayar')
                                <button class="btn btn-primary rounded-pill py-2" id="printInvoice">
                                    <i class="fas fa-print me-2"></i> Cetak Invoice
                                </button>
                                @endif
                                
                                <button class="btn btn-outline-primary rounded-pill py-2" data-bs-toggle="modal" data-bs-target="#contactSupport">
                                    <i class="fas fa-headset me-2"></i> Hubungi Dukungan
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Card -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Ringkasan Biaya</h5>
                            <div class="summary-item">
                                <span>Harga Gedung ({{ $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai) }} jam)</span>
                                <span>Rp {{ number_format($pemesanan->gedung->harga * $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai), 0, ',', '.') }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Biaya Layanan</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="summary-item">
                                <span>Pajak</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="summary-total">
                                <span>Total</span>
                                <span class="fw-bold">Rp {{ number_format($pemesanan->gedung->harga * $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Contact Support Modal -->
<div class="modal fade" id="contactSupport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Hubungi Dukungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Subjek</label>
                        <input type="text" class="form-control" placeholder="Masukkan subjek">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control" rows="4" placeholder="Tulis pesan Anda"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Custom CSS for premium design */
    :root {
        --primary-light: rgba(13, 110, 253, 0.1);
        --success-light: rgba(25, 135, 84, 0.1);
        --warning-light: rgba(255, 193, 7, 0.1);
        --danger-light: rgba(220, 53, 69, 0.1);
        --info-light: rgba(13, 202, 240, 0.1);
    }
    
    .booking-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        margin-bottom: 2rem;
    }
    
    .breadcrumb-light .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .breadcrumb-light .breadcrumb-item.active {
        color: white;
    }
    
    .booking-status-badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .bg-primary-light { background-color: var(--primary-light); }
    .bg-success-light { background-color: var(--success-light); }
    .bg-warning-light { background-color: var(--warning-light); }
    .bg-danger-light { background-color: var(--danger-light); }
    .bg-info-light { background-color: var(--info-light); }
    
    .info-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .info-title {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .timeline-wrapper {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-badge {
        position: absolute;
        left: -1.75rem;
        top: 0;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        border: 3px solid white;
        z-index: 2;
    }
    
    .timeline-line {
        position: absolute;
        left: -0.875rem;
        top: 1.25rem;
        bottom: 0;
        width: 2px;
        z-index: 1;
    }
    
    .duration-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border-radius: 50px;
        font-size: 0.875rem;
    }
    
    .venue-card {
        position: relative;
    }
    
    .venue-image {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .venue-overlay {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
    }
    
    .spec-card {
        display: flex;
        align-items: center;
    }
    
    .spec-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
    }
    
    .facilities-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .facility-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        background-color: #f8f9fa;
        border-radius: 50px;
        font-size: 0.8rem;
    }
    
    .payment-details {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
    }
    
    .payment-method {
        display: flex;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .payment-icon {
        width: 40px;
        height: 40px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.25rem;
        color: #0d6efd;
    }
    
    .info-box h6 {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .info-box p {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .proof-image-container {
        position: relative;
        cursor: pointer;
    }
    
    .proof-image {
        transition: all 0.3s ease;
    }
    
    .proof-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        color: white;
        font-size: 1.5rem;
    }
    
    .proof-image-container:hover .proof-overlay {
        opacity: 1;
    }
    
    .proof-image-container:hover .proof-image {
        transform: scale(1.02);
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 0.875rem;
    }
    
    .summary-divider {
        height: 1px;
        background-color: #dee2e6;
        margin: 0.5rem 0;
    }
    
    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        font-size: 1rem;
    }
    
    @media (max-width: 767.98px) {
        .booking-hero {
            padding: 1.5rem 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Image modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Payment proof image click
        const paymentProofImage = document.getElementById('paymentProofImage');
        if (paymentProofImage) {
            paymentProofImage.addEventListener('click', function() {
                const modalImage = document.getElementById('modalImage');
                modalImage.src = this.src;
                const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
            });
        }
        
        // View gallery button
        const viewGallery = document.getElementById('viewGallery');
        if (viewGallery) {
            viewGallery.addEventListener('click', function() {
                const modalImage = document.getElementById('modalImage');
                modalImage.src = "{{ asset('storage/gedung_images/' . $pemesanan->gedung->first_image) }}";
                const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
            });
        }
        
        // Print invoice button
        const printInvoice = document.getElementById('printInvoice');
        if (printInvoice) {
            printInvoice.addEventListener('click', function() {
                // In a real implementation, this would open a print dialog for the invoice
                alert('Fitur cetak invoice akan membuka pratinjau untuk dicetak');
            });
        }
        
        // Smooth scroll for page load if there's a hash in URL
        if (window.location.hash) {
            setTimeout(function() {
                const element = document.querySelector(window.location.hash);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100);
        }
    });
</script>
@endpush