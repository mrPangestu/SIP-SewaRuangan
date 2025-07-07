@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Payment Header with Progress Indicator -->
        <div class="mb-10">
            <div class="flex justify-center space-x-4 mb-6">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="ml-2 text-sm font-medium text-gray-700">Detail Pesanan</p>
                </div>
                
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-1 w-16 bg-blue-600"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="ml-2 text-sm font-medium text-gray-700">Pembayaran</p>
                </div>
                
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-1 w-16 bg-gray-300"></div>
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="ml-2 text-sm font-medium text-gray-500">Selesai</p>
                </div>
            </div>
            
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Konfirmasi Pembayaran</h1>
                <p class="mt-3 text-lg text-gray-500">ID Pemesanan: <span class="font-medium text-blue-600">{{ $pemesanan->id_pemesanan }}</span></p>
            </div>
        </div>
        
        <!-- Main Payment Card -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Order Summary Card -->
            <div class="lg:w-2/5">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h2 class="text-xl font-bold text-white">Ringkasan Pesanan</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-16 w-16 rounded-lg overflow-hidden bg-gray-100">
                                    @if($pemesanan->gedung->first_image)
                                    <img src="{{ asset('storage/gedung_images/' . $pemesanan->gedung->first_image) }}" 
                                        class="h-full w-full object-cover" 
                                        alt="{{ $pemesanan->gedung->nama }}"
                                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-fallback\'><i class=\'fas fa-image\'></i></div>'" >
                                @else
                                    <div class='image-fallback'><i class='fas fa-image'></i></div>
                                @endif
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $pemesanan->gedung->nama }}</h3>
                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="ml-1">
                                            {{ $pemesanan->tanggal_mulai->format('d F Y H:i') }} - 
                                            {{ $pemesanan->tanggal_selesai->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900">Nama Acara</h4>
                                <p class="mt-1 text-sm text-gray-600">{{ $pemesanan->nama_acara }}</p>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <p class="text-sm font-medium text-gray-900">Subtotal</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <p class="text-sm font-medium text-gray-900">Biaya Layanan</p>
                                    <p class="text-sm text-gray-600">Rp 0</p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between items-center">
                                    <p class="text-base font-medium text-gray-900">Total Pembayaran</p>
                                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Help Section -->
                <div class="mt-6 bg-white rounded-2xl shadow-md overflow-hidden p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Butuh Bantuan?</h3>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Hubungi kami di <a href="mailto:support@example.com" class="text-blue-600 hover:underline">support@example.com</a> atau <a href="tel:+628123456789" class="text-blue-600 hover:underline">+62 812-3456-789</a></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Method Card -->
            <div class="lg:w-3/5">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h2 class="text-xl font-bold text-white">Metode Pembayaran</h2>
                    </div>
                    
                    <form id="paymentForm" action="{{ route('pembayaran.proses') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="id_pemesanan" value="{{ $pemesanan->id_pemesanan }}">
                        
                        <div class="space-y-4 mb-8">
                            @foreach($paymentMethods as $code => $method)
                            <div class="payment-method">
                                <input type="radio" name="metode_pembayaran" 
                                    id="method-{{ $code }}" value="{{ $code }}" 
                                    class="hidden peer" required>
                                <label for="method-{{ $code }}" 
                                    class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                    hover:border-blue-400 hover:bg-blue-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-200">
                                    <div class="flex-shrink-0 w-25 flex items-center justify-center rounded-lg bg-white p-1 border">
                                        <img src="{{ $method['logo'] }}" alt="{{ $method['nama'] }}" class="max-h-full max-w-full">
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-medium text-gray-900">{{ $method['nama'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $method['deskripsi'] }}</p>
                                    </div>
                                    <div class="ml-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity duration-200" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="submit" id="payButton" class="w-full flex justify-center items-center py-4 px-6 border border-transparent rounded-xl shadow-sm text-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            Bayar Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 -mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        
                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500">
                                Dengan melanjutkan, Anda menyetujui <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> dan <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a> kami.
                            </p>
                        </div>

                        


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .payment-method input:checked + label {
        border-color: #3B82F6;
        background-color: #EFF6FF;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    const payButton = document.getElementById('payButton');
    
    paymentForm.addEventListener('submit', function(e) {
        payButton.disabled = true;
        payButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses Pembayaran...
        `;
        
        // Add loading class to form
        paymentForm.classList.add('opacity-75');
        // paymentForm.querySelectorAll('input, button').forEach(el => {
        paymentForm.querySelectorAll('button').forEach(el => {
            el.disabled = true;
        });
    });
    
    // Add animation to payment method selection
    document.querySelectorAll('.payment-method input').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method label').forEach(label => {
                label.classList.remove('ring-2', 'ring-blue-200');
            });
            
            if (this.checked) {
                const label = document.querySelector(`label[for="${this.id}"]`);
                label.classList.add('ring-2', 'ring-blue-200');
                
                // Add subtle animation
                label.style.transform = 'scale(1.01)';
                setTimeout(() => {
                    label.style.transform = '';
                }, 200);
            }
        });
    });
});
</script>
@endpush