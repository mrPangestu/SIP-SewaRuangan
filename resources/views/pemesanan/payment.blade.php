@extends('layouts.app')

@section('title', 'Pembayaran Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="p-6 bg-blue-600 text-white">
                <h1 class="text-2xl font-bold">Lanjutkan Pembayaran</h1>
                <p>ID Pemesanan: {{ $pemesanan->id_pemesanan }}</p>
            </div>
            
            <div class="md:flex">
                <!-- Detail Pemesanan -->
                <div class="md:w-1/2 p-6 border-r">
                    <h2 class="text-lg font-semibold mb-4">Detail Pemesanan</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-gray-600">Gedung</p>
                            <p class="font-medium">{{ $pemesanan->gedung->nama }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600">Tanggal & Waktu</p>
                            <p class="font-medium">
                                {{ $pemesanan->tanggal_mulai->format('d F Y') }} |
                                {{ $pemesanan->tanggal_mulai->format('H:i') }} - {{ $pemesanan->tanggal_selesai->format('H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600">Nama Acara</p>
                            <p class="font-medium">{{ $pemesanan->nama_acara }}</p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600">Durasi</p>
                            <p class="font-medium">{{ $pemesanan->durasi }} Jam</p>
                        </div>
                    </div>
                </div>
                
                <!-- Metode Pembayaran -->
                <div class="md:w-1/2 p-6">
                    <h2 class="text-lg font-semibold mb-4">Pembayaran</h2>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-gray-600">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-blue-600">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Sudah termasuk pajak dan biaya layanan
                        </p>
                    </div>
                    
                    <form action="{{ route('pembayaran.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_pemesanan" value="{{ $pemesanan->id_pemesanan }}">
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Pilih Metode Pembayaran</label>
                            <div class="space-y-3">
                                <div class="flex items-center p-3 border rounded-lg hover:border-blue-400">
                                    <input type="radio" name="metode_pembayaran" id="bank-transfer" 
                                        value="bank_transfer" class="mr-3" checked>
                                    <label for="bank-transfer" class="flex-1 cursor-pointer">
                                        <p class="font-medium">Transfer Bank</p>
                                        <p class="text-sm text-gray-500">BCA, BRI, Mandiri, dll</p>
                                    </label>
                                    <img src="{{ asset('images/bank.png') }}" alt="Bank Transfer" class="h-6">
                                </div>
                                
                                <div class="flex items-center p-3 border rounded-lg hover:border-blue-400">
                                    <input type="radio" name="metode_pembayaran" id="gopay" 
                                        value="gopay" class="mr-3">
                                    <label for="gopay" class="flex-1 cursor-pointer">
                                        <p class="font-medium">GoPay</p>
                                        <p class="text-sm text-gray-500">Bayar dengan GoPay</p>
                                    </label>
                                    <img src="{{ asset('images/gopay.png') }}" alt="GoPay" class="h-6">
                                </div>
                                
                                <div class="flex items-center p-3 border rounded-lg hover:border-blue-400">
                                    <input type="radio" name="metode_pembayaran" id="ovo" 
                                        value="ovo" class="mr-3">
                                    <label for="ovo" class="flex-1 cursor-pointer">
                                        <p class="font-medium">OVO</p>
                                        <p class="text-sm text-gray-500">Bayar dengan OVO</p>
                                    </label>
                                    <img src="{{ asset('images/ovo.png') }}" alt="OVO" class="h-6">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white 
                            py-3 px-4 rounded-lg font-medium transition duration-300">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection