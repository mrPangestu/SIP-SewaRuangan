

function initModal() {
    // Pastikan modal tertutup saat pertama kali load
    closeBookingModal();
    
    window.openBookingModal = function() {
        document.getElementById('bookingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeBookingModal = function() {
        document.getElementById('bookingModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
    
    // Tambahkan event listener untuk tombol close
    document.querySelector('.modal-close').addEventListener('click', closeBookingModal);
}

// Panggil initModal saat DOM siap
document.addEventListener('DOMContentLoaded', function() {
    initModal();
    // ... fungsi lainnya ...
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar and booking functionality
    initCalendar();
    initBookingForm();
    initModal();
    initGallery();
});

function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    const currentMonthEl = document.getElementById('currentMonth');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const bookingsContainer = document.getElementById('bookingsContainer');
    const bookingsList = document.getElementById('bookingsList');
    const noBookings = document.getElementById('noBookings');
    const selectedDateText = document.getElementById('selectedDateText');
    
    let currentDate = new Date();
    let selectedDate = null;
    
    // Render the initial calendar
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
            dayEl.className = 'calendar-day-header';
            dayEl.textContent = day;
            calendarEl.appendChild(dayEl);
        });
        
        // Calculate first day and days in month
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        
        // Empty cells for days before first day
        for (let i = 0; i < firstDay; i++) {
            const emptyEl = document.createElement('div');
            emptyEl.className = 'calendar-day disabled';
            calendarEl.appendChild(emptyEl);
        }
        
        // Render days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            const dateStr = formatDate(date);
            const isBooked = gedungData.bookedDates.includes(dateStr);
            const isToday = isSameDay(date, new Date());
            
            const dayEl = document.createElement('button');
            dayEl.className = 'calendar-day';
            dayEl.textContent = day;
            
            if (isBooked) {
                dayEl.classList.add('booked');
            }
            
            if (isToday) {
                dayEl.classList.add('today');
            }
            
            if (selectedDate === dateStr) {
                dayEl.classList.add('selected');
            }
            
            dayEl.addEventListener('click', () => handleDateClick(dateStr, dayEl));
            calendarEl.appendChild(dayEl);
        }
    }
    
    function handleDateClick(dateStr, dayEl) {
        // Clear previous selection
        document.querySelectorAll('.calendar-day').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Set new selection
        dayEl.classList.add('selected');
        selectedDate = dateStr;
        
        // Load bookings for selected date
        loadBookings(dateStr);
    }
    
    function loadBookings(dateStr) {
        fetch(`/gedung/${gedungData.id}/bookings/${dateStr}`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to load bookings');
                return response.json();
            })
            .then(bookings => displayBookings(dateStr, bookings))
            .catch(error => {
                console.error('Error loading bookings:', error);
                showToast('Gagal memuat data pemesanan', 'error');
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
                const bookingEl = document.createElement('div');
                bookingEl.className = 'booking-item';
                bookingEl.innerHTML = `
                    <h4 class="booking-item-title">${booking.nama_acara}</h4>
                    <div class="booking-item-time">
                        <span>${booking.waktu_mulai} - ${booking.waktu_selesai}</span>
                        <span>${booking.durasi} Jam</span>
                    </div>
                `;
                bookingsList.appendChild(bookingEl);
            });
        }
        
        bookingsContainer.classList.remove('hidden');
    }
    
    // Navigation buttons
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    // Helper functions
    function formatDate(date) {
        const pad = num => num.toString().padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}`;
    }
    
    function isSameDay(date1, date2) {
        return date1.getFullYear() === date2.getFullYear() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getDate() === date2.getDate();
    }
}

function initBookingForm() {
    const form = document.getElementById('bookingForm');
    const tanggalMulaiInput = document.getElementById('tanggal_mulai');
    const durasiSelect = document.getElementById('durasi');
    const totalHarga = document.getElementById('totalHarga');
    const durationDisplay = document.getElementById('durationDisplay');
    
    if (durasiSelect && totalHarga) {
        // Update price when duration changes
        durasiSelect.addEventListener('change', function() {
            const durasi = this.value;
            const total = gedungData.harga * durasi;
            totalHarga.textContent = 'Rp ' + total.toLocaleString('id-ID');
            durationDisplay.textContent = durasi + ' Jam';
        });
    }
    
    if (tanggalMulaiInput) {
        // Set minimum datetime (2 hours from now)
        const now = new Date();
        now.setHours(now.getHours() + 2);
        tanggalMulaiInput.min = formatDateTimeLocal(now);
        
        // Check availability when date changes
        tanggalMulaiInput.addEventListener('change', checkAvailability);
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const startTime = new Date(tanggalMulaiInput.value);
            const now = new Date();
            
            if (startTime < now) {
                e.preventDefault();
                showToast('Waktu pemesanan tidak boleh di masa lalu', 'error');
                return false;
            }
            
            const minTime = new Date(now.getTime() + 2 * 60 * 60 * 1000);
            if (startTime < minTime) {
                e.preventDefault();
                showToast('Pemesanan harus dibuat minimal 2 jam sebelum waktu mulai', 'error');
                return false;
            }
            
            return true;
        });
    }
    
    function checkAvailability() {
        const tanggalMulai = tanggalMulaiInput.value;
        const durasi = durasiSelect.value;
        
        if (!tanggalMulai || !durasi) return;
        
        fetch(`/api/gedung/${gedungData.id}/check-availability`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': gedungData.csrfToken
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
                showToast('Gedung sudah dipesan pada waktu tersebut atau kurang dari 2 jam sebelum/sesudahnya', 'error');
            }
        })
        .catch(error => {
            console.error('Error checking availability:', error);
            showToast('Gagal memeriksa ketersediaan gedung', 'error');
        });
    }
    
    function formatDateTimeLocal(date) {
        const pad = num => num.toString().padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }
}

function initModal() {
    window.openBookingModal = function() {
        document.getElementById('bookingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeBookingModal = function() {
        document.getElementById('bookingModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
}

function initGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.querySelector('.main-image img');
    
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            mainImage.src = imgSrc;
            
            // Add active state to clicked thumbnail
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}

// Add toast styles dynamically
const toastStyles = document.createElement('style');
toastStyles.textContent = `
.toast-notification {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.toast-notification.show {
    opacity: 1;
}
.toast-success {
    background: #10b981;
}
.toast-error {
    background: #ef4444;
}
.toast-warning {
    background: #f59e0b;
}
`;
document.head.appendChild(toastStyles);