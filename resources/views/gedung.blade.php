@extends('layouts.app')

@section('title', 'Gedung A')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Gedung Details -->
        <div class="md:w-1/2">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Gedung A</h1>
            
            <p class="text-gray-600 mb-6">
                Lorem ipsum dolor sit amet consectetur adipiscing elit. Duis aiguilla ne feugiat, sed do eiusmod tempor 
                incididunt ut labore et dolore magna aliqua. Nullam est laboris nisi ut aliquip ex ea commodo consequat. 
                Excepteur sint occaecat nonummy nibh euismod tincidunt ut labore et dolore magna aliqua.
            </p>

            <div class="space-y-4 mb-6">
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Kapasitas:</span>
                    <div>
                        <p>180-200 orang</p>
                        <p>240-400 orang (dengan perluasan)</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Harga:</span>
                    <span>Rp 150.000 - Rp 600.000 / jam</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Produk:</span>
                    <span>Samsung, Kens Apple, Texas AC</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Departemen:</span>
                    <span>1M - Samsung</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Tipe:</span>
                    <span>Gedung A</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Kode:</span>
                    <span>GIFD 012456</span>
                </div>
                <div class="flex items-start">
                    <span class="font-semibold text-gray-700 w-32">Email:</span>
                    <span>contact@gedunga.com</span>
                </div>
            </div>
        </div>

        <!-- Booking Section -->
        <div class="md:w-1/2 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pesan Gedung</h2>
            
            <!-- Dynamic Calendar -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-medium text-gray-700">Pilih Tanggal</h3>
                    <div class="flex space-x-2">
                        <button id="prevMonth" class="p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="nextMonth" class="p-1 rounded hover:bg-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <div id="calendar" class="grid grid-cols-7 gap-1 mb-4">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>

            <!-- Time Selection -->
            <div class="mb-6">
                <h3 class="font-medium text-gray-700 mb-2">Pilih Waktu</h3>
                <div class="grid grid-cols-3 gap-2" id="timeSlots">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
            </div>

            <!-- Booking Form -->
            <form id="bookingForm">
                <div class="mb-4">
                    <label for="eventName" class="block text-gray-700 mb-2">Nama Acara</label>
                    <input type="text" id="eventName" name="eventName" class="w-full px-3 py-2 border rounded-lg">
                </div>
                
                <div class="mb-4">
                    <label for="duration" class="block text-gray-700 mb-2">Durasi (jam)</label>
                    <select id="duration" name="duration" class="w-full px-3 py-2 border rounded-lg">
                        <option value="1">1 Jam</option>
                        <option value="2">2 Jam</option>
                        <option value="3">3 Jam</option>
                        <option value="4">4 Jam</option>
                        <option value="5">5 Jam</option>
                        <option value="6">6 Jam</option>
                        <option value="7">7 Jam</option>
                        <option value="8">8 Jam</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">
                    Pesan Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let selectedDate = null;
    const calendarEl = document.getElementById('calendar');
    const timeSlotsEl = document.getElementById('timeSlots');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    
    // Sample availability data (in a real app, this would come from an API)
    const availability = {
        '2023-11-15': ['08:00', '10:00', '14:00'],
        '2023-11-16': ['09:00', '13:00', '15:00'],
        '2023-11-20': ['10:00', '12:00', '16:00'],
        '2023-12-05': ['08:00', '11:00', '14:00'],
    };

    // Render calendar
    function renderCalendar() {
        calendarEl.innerHTML = '';
        
        // Month and year header
        const monthYear = document.createElement('div');
        monthYear.className = 'col-span-7 text-center font-semibold text-gray-700 mb-2';
        monthYear.textContent = new Intl.DateTimeFormat('id-ID', { 
            month: 'long', 
            year: 'numeric' 
        }).format(currentDate);
        calendarEl.appendChild(monthYear);
        
        // Day names
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        days.forEach(day => {
            const dayEl = document.createElement('div');
            dayEl.className = 'text-center font-medium text-gray-600 py-1';
            dayEl.textContent = day;
            calendarEl.appendChild(dayEl);
        });
        
        // Get first day of month and total days
        const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
        
        // Empty cells for days before first day
        for (let i = 0; i < firstDay; i++) {
            const emptyEl = document.createElement('div');
            emptyEl.className = 'h-10';
            calendarEl.appendChild(emptyEl);
        }
        
        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);
            const dateStr = formatDate(date);
            const isAvailable = availability[dateStr] !== undefined;
            const isSelected = selectedDate === dateStr;
            
            const dayEl = document.createElement('button');
            dayEl.className = `h-10 rounded-full flex items-center justify-center 
                ${isAvailable ? 'hover:bg-blue-100 cursor-pointer' : 'text-gray-400 cursor-not-allowed'}
                ${isSelected ? 'bg-blue-500 text-white' : ''}`;
            dayEl.textContent = day;
            dayEl.disabled = !isAvailable;
            
            if (isAvailable) {
                dayEl.addEventListener('click', () => {
                    selectedDate = dateStr;
                    renderCalendar();
                    renderTimeSlots();
                });
            }
            
            calendarEl.appendChild(dayEl);
        }
    }
    
    // Render time slots
    function renderTimeSlots() {
        timeSlotsEl.innerHTML = '';
        
        if (!selectedDate) return;
        
        const slots = availability[selectedDate] || [];
        
        if (slots.length === 0) {
            timeSlotsEl.innerHTML = '<p class="col-span-3 text-gray-500">Tidak ada slot waktu tersedia</p>';
            return;
        }
        
        slots.forEach(slot => {
            const slotEl = document.createElement('button');
            slotEl.className = 'py-2 px-3 border rounded-lg hover:bg-blue-100 transition';
            slotEl.textContent = slot;
            slotEl.addEventListener('click', () => {
                document.getElementById('timeSlots').querySelectorAll('button').forEach(btn => {
                    btn.classList.remove('bg-blue-500', 'text-white');
                    btn.classList.add('border');
                });
                slotEl.classList.add('bg-blue-500', 'text-white');
                slotEl.classList.remove('border');
            });
            timeSlotsEl.appendChild(slotEl);
        });
    }
    
    // Helper function to format date as YYYY-MM-DD
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }
    
    // Event listeners for month navigation
    prevMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });
    
    nextMonthBtn.addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
    
    // Form submission
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!selectedDate) {
            alert('Silakan pilih tanggal terlebih dahulu');
            return;
        }
        
        // Here you would typically send the booking data to your backend
        alert('Pemesanan berhasil! Kami akan menghubungi Anda untuk konfirmasi.');
    });
    
    // Initial render
    renderCalendar();
});
</script>
@endpush