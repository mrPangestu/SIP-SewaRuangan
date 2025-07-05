@extends('layouts.app')

@section('title', 'Redirect Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <img src="{{ asset('images/payment/processing.gif') }}" alt="Processing" class="h-32 mx-auto">
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Mengarahkan ke Pembayaran</h1>
            <p class="text-gray-600 mb-6">
                Anda akan diarahkan ke halaman pembayaran. Jangan tutup atau refresh halaman ini.
            </p>
            
            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                <p class="font-medium">Total Pembayaran</p>
                <p class="text-xl text-blue-600 font-bold">
                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $paymentMethods[$pembayaran->metode_pembayaran]['nama'] }}
                </p>
            </div>
            
            <div id="countdown" class="text-gray-500 text-sm mb-6">
                Redirect dalam: <span id="timer">5</span> detik
            </div>
            
            <a href="{{ route('pembayaran.show', $pembayaran->id_pemesanan) }}" 
                class="text-blue-600 hover:text-blue-800 font-medium">
                Kembali ke halaman pembayaran
            </a>
        </div>
    </div>
</div>

<script>
// Auto redirect simulation
let seconds = 5;
const timer = document.getElementById('timer');

const countdown = setInterval(() => {
    seconds--;
    timer.textContent = seconds;
    
    if (seconds <= 0) {
        clearInterval(countdown);
        // In real implementation, this would redirect to payment gateway
        window.location.href = "{{ route('pembayaran.sukses', $pembayaran->id_pembayaran) }}";
    }
}, 1000);
</script>
@endsection