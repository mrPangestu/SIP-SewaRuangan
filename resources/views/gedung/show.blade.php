@extends('layouts.app')

@section('title', $gedung->nama)

@section('content')
<div class="gedung-detail-container">
    <!-- Hero Section -->
    <section class="gedung-hero">
        <div class="hero-overlay"></div>
        <div class="container mx-auto px-4 py-24 relative z-10">
            <h1 class="hero-title">{{ $gedung->nama }}</h1>
            <div class="hero-meta">
                <span class="meta-item"><i class="fas fa-map-marker-alt"></i> {{ $gedung->lokasi }}</span>
                <span class="meta-item"><i class="fas fa-users"></i> {{ $gedung->kapasitas }} orang</span>
                <span class="meta-item"><i class="fas fa-tags"></i> Rp {{ number_format($gedung->harga, 0, ',', '.') }} / jam</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column -->
            <div class="lg:w-2/3">
                <!-- Gallery -->
                <div class="gedung-gallery mb-12">
                    <div class="main-image">
                        @if($gedung->first_image)
                            <img src="{{ asset('storage/gedung_images/' . $gedung->first_image) }}" 
                                class="building-image" 
                                alt="{{ $gedung->nama }}"
                                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-fallback\'><i class=\'fas fa-image\'></i></div>'">
                        @else
                            <div class='image-fallback'><i class='fas fa-image'></i></div>
                        @endif
                    </div>
                    <div class="thumbnail-grid">
                        @php
                            // Split the image string into an array
                            $images = explode(',', $gedung->image);
                            // Take only first 3 images (as per your seeder)
                            $displayImages = array_slice($images, 0, 4);
                        @endphp
                        
                        @foreach($displayImages as $image)
                        <div class="thumbnail-item">
                            <img src="{{ asset('storage/gedung_images/' . trim($image)) }}" 
                                alt="Thumbnail {{ $loop->iteration }}" 
                                class="w-full h-full object-cover rounded-lg"
                                onerror="this.onerror=null; this.src='https://source.unsplash.com/random/300x200/?building,event,{{ $loop->index }}'">
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description Section -->
                <section class="mb-12">
                    <h2 class="section-title">Deskripsi Gedung</h2>
                    <div class="section-content">
                        <p>{{ $gedung->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                    </div>
                </section>

                <!-- Features Section -->
                <section class="mb-12">
                    <h2 class="section-title">Fasilitas</h2>
                    <div class="features-grid">
                        @foreach(explode(',', $gedung->fasilitas) as $facility)
                        <div class="feature-item">
                            <i class="fas fa-check-circle feature-icon"></i>
                            <span>{{ trim($facility) }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <!-- Right Column -->
            <div class="lg:w-1/3">
                <!-- Booking Card -->
                <div class="booking-card">
                    <div class="booking-header">
                        <h3 class="booking-title">Pesan Gedung Ini</h3>
                        <p class="booking-price">Rp {{ number_format($gedung->harga, 0, ',', '.') }} <span class="price-unit">/jam</span></p>
                    </div>

                    @auth
                        <button onclick="openBookingModal()" class="booking-button">
                            <i class="fas fa-calendar-alt mr-2"></i> Pilih Tanggal
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="booking-button">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Memesan
                        </a>
                    @endauth

                    <div class="booking-details">
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <span class="detail-label">Kapasitas</span>
                                <span class="detail-value">{{ $gedung->kapasitas }} orang</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-layer-group"></i>
                            <div>
                                <span class="detail-label">Kategori</span>
                                <span class="detail-value">{{ $gedung->kategori->nama_kategori }}</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <span class="detail-label">Lokasi</span>
                                <span class="detail-value">{{ $gedung->lokasi }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Availability Calendar -->
                <div class="availability-card mt-8">
                    <h3 class="availability-title">Ketersediaan</h3>
                    <div class="calendar-header">
                        <button id="prevMonth" class="calendar-nav">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <h4 id="currentMonth" class="calendar-month"></h4>
                        <button id="nextMonth" class="calendar-nav">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="calendar-grid" id="calendar">
                        <!-- Calendar will be populated by JavaScript -->
                    </div>
                    
                    <div id="bookingsContainer" class="bookings-container hidden">
                        <h4 class="bookings-title">
                            Pemesanan pada <span id="selectedDateText" class="bookings-date"></span>
                        </h4>
                        <div id="noBookings" class="no-bookings">
                            <i class="fas fa-calendar-check"></i>
                            <p>Tidak ada pemesanan pada tanggal ini</p>
                        </div>
                        <div id="bookingsList" class="bookings-list">
                            <!-- Bookings will appear here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal-container hidden">
    <div class="modal-overlay" onclick="closeBookingModal()" ></div>
    <div class="modal-content1" >
        <div class="modal-header mx-4 my-2">
            <h3 class="modal-title">Pesan {{ $gedung->nama }}</h3>
            <button onclick="closeBookingModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('pemesanan.store') }}" method="POST" class="modal-form">
            @csrf
            <input type="hidden" name="id_gedung" value="{{ $gedung->id_gedung }}">
        
            <div class="form-group">
                <label for="tanggal_mulai" class="form-label">
                    <i class="fas fa-calendar-day mr-2"></i> Tanggal & Waktu Mulai
                </label>
                <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" 
                    min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" 
                    class="form-input" required>
                <p class="form-note">Minimal 2 jam dari sekarang</p>
            
                @error('tanggal_mulai')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        
            <div class="form-group">
                <label for="durasi" class="form-label">
                    <i class="fas fa-clock mr-2"></i> Durasi (jam)
                </label>
                <select id="durasi" name="durasi" class="form-input" required>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }} Jam</option>
                    @endfor
                </select>
            </div>
        
            <div class="form-group">
                <label for="nama_acara" class="form-label">
                    <i class="fas fa-star mr-2"></i> Nama Acara
                </label>
                <input type="text" id="nama_acara" name="nama_acara" maxlength="30"
                    class="form-input" placeholder="Contoh: Seminar Kewirausahaan" required>
            </div>
        
            <div class="price-summary">
                <div class="price-row">
                    <span>Harga per jam</span>
                    <span>Rp {{ number_format($gedung->harga, 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span>Durasi</span>
                    <span id="durationDisplay">1 Jam</span>
                </div>
                <div class="price-total">
                    <span>Total Pembayaran</span>
                    <span id="totalHarga">Rp {{ number_format($gedung->harga, 0, ',', '.') }}</span>
                </div>
            </div>
        
            <button type="submit" class="submit-button">
                <i class="fas fa-credit-card mr-2"></i> Lanjutkan ke Pembayaran
            </button>
        </form>
    </div>
</div>

@endsection

@push('styles')
<link href="{{ asset('css/style-detailGedung.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
<script src="{{ asset('js/script-detailGedung.js') }}"></script>
<script>
    // Pass PHP variables to JavaScript
    const gedungData = {
        id: "{{ $gedung->id_gedung }}",
        harga: {{ $gedung->harga }},
        bookedDates: @json($bookedDates),
        csrfToken: "{{ csrf_token() }}"
    };
</script>
@endpush