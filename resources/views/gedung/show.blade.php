@extends('layouts.app')

@section('title', $gedung->nama)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Gedung Details -->
        <div class="md:w-1/2">

            
            

            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $gedung->nama }}</h1>
            
            <p class="text-gray-600 mb-6">
                {{ $gedung->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
            </p>

            <div class="space-y-4 mb-6">
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Lokasi:</span>
                    <span>{{ $gedung->lokasi }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Kapasitas:</span>
                    <span>{{ $gedung->kapasitas }} orang</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Harga:</span>
                    <span>Rp {{ number_format($gedung->harga, 0, ',', '.') }} / jam</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Fasilitas:</span>
                    <span>{{ $gedung->fasilitas }}</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Kategori:</span>
                    <span>{{ $gedung->kategori->nama_kategori }}</span>
                </div>
            </div>

            @auth
                <button onclick="openBookingModal()" 
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300">
                    Pesan Gedung Ini
                </button>
            @else
                <a href="{{ route('login') }}"><button
                    class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition duration-300">
                    Pesan Gedung Ini
                </button></a>
            @endauth

            
        </div>

        <!-- Calendar Section -->
        <div class="md:w-1/2 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Kalender Ketersediaan</h2>
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-700">Pilih Tanggal</h3>
                    <div class="flex space-x-2">
                        <button id="prevMonth" class="p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <span id="currentMonth" class="font-medium"></span>
                        <button id="nextMonth" class="p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <div id="calendar" class="grid grid-cols-7 gap-1 mb-4">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>

            <!-- Bookings List -->
            <div id="bookingsContainer" class="hidden mt-6 bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-lg mb-3">
                    Pemesanan pada <span id="selectedDateText" class="text-blue-600"></span>
                </h3>
                <div id="noBookings" class="hidden text-gray-500 text-center py-4">
                    Tidak ada pemesanan pada tanggal ini
                </div>
                <div id="bookingsList" class="space-y-3">
                    <!-- Daftar pemesanan akan muncul di sini -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Pesan {{ $gedung->nama }}</h2>
            <button onclick="closeBookingModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('pemesanan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_gedung" value={{ $gedung->id_gedung }}>
        
            <div class="mb-4">
                <label for="tanggal_mulai" class="block text-gray-700 mb-2">Tanggal & Waktu Mulai</label>
                <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" 
                    min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" 
                    class="w-full px-3 py-2 border rounded-lg" required>
                <p class="text-sm text-gray-500 mt-1">Minimal 2 jam dari sekarang</p>
            
                @error('tanggal_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        
            <div class="mb-4">
                <label for="durasi" class="block text-gray-700 mb-2">Durasi (jam)</label>
                <select id="durasi" name="durasi" class="w-full px-3 py-2 border rounded-lg" required>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">{{ $i }} Jam</option>
                    @endfor
                </select>
            </div>
        
            <div class="mb-4">
                <label for="nama_acara" class="block text-gray-700 mb-2">Nama Acara</label>
                <input type="text" id="nama_acara" name="nama_acara" maxlength="30"
                    class="w-full px-3 py-2 border rounded-lg" required>
            </div>
        
            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                <p class="text-gray-600">Total Pembayaran:</p>
                <p class="text-xl font-bold text-blue-600" id="totalHarga">
                    Rp {{ number_format($gedung->harga, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-500">*Harga per jam: Rp {{ number_format($gedung->harga, 0, ',', '.') }}</p>
            </div>
        
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                Lanjutkan ke Pembayaran
            </button>
        </form>
    </div>
</div>
<div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

@endsection

@push('scripts')
<script>

function directLogin() {
            window.location.href = "{{ route('login') }}";
    }

document.addEventListener('DOMContentLoaded', function() {
    const hargaPerJam = {{ $gedung->harga }};
    const durasiSelect = document.getElementById('durasi');
    const totalHarga = document.getElementById('totalHarga');

    durasiSelect.addEventListener('change', function() {
        const durasi = this.value;
        const total = hargaPerJam * durasi;
        totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const gedungId = "{{ $gedung->id_gedung }}";
    let currentDate = new Date();
    let selectedDate = null;
    
    // Calendar elements
    const calendarEl = document.getElementById('calendar');
    const currentMonthEl = document.getElementById('currentMonth');
    const bookingsContainer = document.getElementById('bookingsContainer');
    const bookingsList = document.getElementById('bookingsList');
    const noBookings = document.getElementById('noBookings');
    const selectedDateText = document.getElementById('selectedDateText');
    
    // Navigation buttons
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    // Booked dates from controller
    const bookedDates = @json($bookedDates);

    // Initialize calendar
    renderCalendar();

    function renderCalendar() {
        calendarEl.innerHTML = '';
        
        // Set current month display
        currentMonthEl.textContent = new Intl.DateTimeFormat('id-ID', { 
            month: 'long', 
            year: 'numeric' 
        }).format(currentDate);
        
        // Render day names
        ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].forEach(day => {
            const dayEl = document.createElement('div');
            dayEl.className = 'text-center font-medium text-gray-600 py-1';
            dayEl.textContent = day;
            calendarEl.appendChild(dayEl);
        });
        
        // Calculate first day and days in month
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        
        // Empty cells for days before first day
        for (let i = 0; i < firstDay; i++) {
            calendarEl.appendChild(createEmptyDay());
        }
        
        // Render days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            const dateStr = formatDate(date);
            const isBooked = bookedDates.includes(dateStr);
            
            const dayEl = createDayElement(day, dateStr, isBooked);
            calendarEl.appendChild(dayEl);
        }
    }
    
    function createEmptyDay() {
        const emptyEl = document.createElement('div');
        emptyEl.className = 'h-10';
        return emptyEl;
    }
    
    function createDayElement(day, dateStr, isBooked) {
        const dayContainer = document.createElement('div');
        dayContainer.className = 'relative h-10';
        
        const dayEl = document.createElement('button');
        dayEl.className = `calendar-day w-full h-full rounded-full flex items-center justify-center 
            border border-transparent hover:border-gray-300 cursor-pointer
            ${selectedDate === dateStr ? 'bg-blue-100 border-blue-400' : ''}`;
        dayEl.textContent = day;
        
        dayEl.addEventListener('click', () => handleDateClick(dateStr, dayEl));
        
        dayContainer.appendChild(dayEl);
        
        // Add red dot indicator if booked
        if (isBooked) {
            const dotEl = document.createElement('div');
            dotEl.className = 'absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full';
            dayContainer.appendChild(dotEl);
        }
        
        return dayContainer;
    }
    
    function handleDateClick(dateStr, dayEl) {
        // Clear previous selection
        document.querySelectorAll('.calendar-day').forEach(el => {
            el.classList.remove('bg-blue-100', 'border-blue-400');
        });
        
        // Set new selection
        dayEl.classList.add('bg-blue-100', 'border-blue-400');
        selectedDate = dateStr;
        
        // Load bookings for selected date
        loadBookings(dateStr);
    }
    
    function loadBookings(dateStr) {
        fetch(`/gedung/${gedungId}/bookings/${dateStr}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load bookings');
                return response.json();
            })
            .then(bookings => displayBookings(dateStr, bookings))
            .catch(error => {
                console.error('Error loading bookings:', error);
                alert('Gagal memuat data pemesanan');
            });
    }
    
    function displayBookings(dateStr, bookings) {
        // Clear previous bookings
        bookingsList.innerHTML = '';
        
        // Format date for display
        const dateObj = new Date(dateStr);
        selectedDateText.textContent = dateObj.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
        
        if (!bookings || bookings.length === 0) {
            noBookings.classList.remove('hidden');
            bookingsList.classList.add('hidden');
        } else {
            noBookings.classList.add('hidden');
            bookingsList.classList.remove('hidden');
            
            bookings.forEach(booking => {
                bookingsList.appendChild(createBookingElement(booking));
            });
        }
        
        bookingsContainer.classList.remove('hidden');
    }
    
    function createBookingElement(booking) {
        const bookingEl = document.createElement('div');
        bookingEl.className = 'p-3 bg-white rounded-lg shadow-sm border mb-2';
        bookingEl.innerHTML = `
            <h4 class="font-medium text-gray-800">${booking.nama_acara}</h4>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-600">${booking.waktu_mulai} - ${booking.waktu_selesai}</span>
                <span class="text-gray-500">${booking.durasi}</span>
            </div>
        `;
        return bookingEl;
    }
    
    function formatDate(date) {
        const pad = num => num.toString().padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}`;
    }
    
    // Month navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    // Modal handling
    window.openBookingModal = function() {
        document.getElementById('modalBackdrop').classList.remove('hidden');
        document.getElementById('bookingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('bookingDate').min = formatDate(new Date());
    };
    
    window.closeBookingModal = function() {
        document.getElementById('modalBackdrop').classList.add('hidden');
        document.getElementById('bookingModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
    
    document.getElementById('modalBackdrop').addEventListener('click', closeBookingModal);
});








// resources/js/check-availability.js
document.addEventListener('DOMContentLoaded', function() {
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const idGedung = document.querySelector('input[name="id_gedung"]').value;
    const durasiSelect = document.getElementById('durasi');
    
    if (tanggalMulaiInput) {
        tanggalMulaiInput.addEventListener('change', checkAvailability);
    }
    
    if (durasiSelect) {
        durasiSelect.addEventListener('change', checkAvailability);
    }

    function checkAvailability() {
        const tanggalMulai = tanggalMulaiInput.value;
        const durasi = durasiSelect.value;
        
        if (!tanggalMulai || !durasi) return;
        
        fetch(`/api/gedung/${idGedung}/check-availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                tanggal_mulai: tanggalMulai,
                durasi: durasi
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                tanggalMulaiInput.setCustomValidity('');
            } else {
                tanggalMulaiInput.setCustomValidity('Gedung tidak tersedia pada waktu tersebut');
                alert('Gedung sudah dipesan pada waktu tersebut atau kurang dari 2 jam sebelum/sesudahnya');
            }
        });
    }
});






document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    const startTimeInput = document.getElementById('tanggal_mulai');
    
    form.addEventListener('submit', function(e) {
        const selectedTime = new Date(startTimeInput.value);
        const now = new Date();
        const minTime = new Date(now.getTime() + 2 * 60 * 60 * 1000); // 2 jam dari sekarang
        
        if (selectedTime < now) {
            e.preventDefault();
            alert('Waktu pemesanan tidak boleh di masa lalu');
            return false;
        }
        
        if (selectedTime < minTime) {
            e.preventDefault();
            alert('Pemesanan harus dibuat minimal 2 jam sebelum waktu mulai');
            return false;
        }
        
        return true;
    });
});
</script>
@endpush