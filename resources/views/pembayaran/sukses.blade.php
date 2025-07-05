@extends('app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto text-center">
        <div class="max-w-md mx-auto text-center bg-white rounded-lg shadow-md p-6">
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-check text-green-500 text-3xl"></i>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mb-6">
                Terima kasih telah melakukan pembayaran. Pemesanan Anda telah dikonfirmasi.
            </p>
            
            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                <p class="font-medium">ID Transaksi</p>
                <p class="text-lg text-blue-600 font-bold mb-2">
                    {{ $pembayaran->referensi_pembayaran }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $pembayaran->waktu_pembayaran->format('d F Y H:i') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Metode: {{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}
                </p>
            </div>
            
            <div class="flex flex-col space-y-3">
                <a href="{{ route('pemesanan.show', $pembayaran->id_pemesanan) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition">
                    Lihat Detail Pemesanan
                </a>
                <a href="{{ route('beranda') }}" 
                    class="text-blue-600 hover:text-blue-800 font-medium">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection