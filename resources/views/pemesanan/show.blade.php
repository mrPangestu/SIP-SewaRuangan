@extends('app')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pemesanan</h1>
            <span class="px-3 py-1 rounded-full text-sm font-medium 
                @if($pemesanan->status === 'dibayar') bg-green-100 text-green-800
                @elseif($pemesanan->status === 'menunggu_pembayaran') bg-yellow-100 text-yellow-800
                @elseif($pemesanan->status === 'dibatalkan') bg-red-100 text-red-800
                @else bg-blue-100 text-blue-800 @endif">
                {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
            </span>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Informasi Pemesanan -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold mb-4">Informasi Pemesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">ID Pemesanan</p>
                        <p class="font-medium">{{ $pemesanan->id_pemesanan }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Pemesanan</p>
                        <p class="font-medium">{{ $pemesanan->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Nama Acara</p>
                        <p class="font-medium">{{ $pemesanan->nama_acara }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Pembayaran</p>
                        <p class="font-medium text-blue-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Jadwal -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold mb-4">Jadwal</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Tanggal Mulai</p>
                        <p class="font-medium">{{ $pemesanan->tanggal_mulai->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Selesai</p>
                        <p class="font-medium">{{ $pemesanan->tanggal_selesai->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Durasi</p>
                        <p class="font-medium">{{ $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai) }} Jam</p>
                    </div>
                </div>
            </div>

            <!-- Detail Gedung -->
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold mb-4">Detail Gedung</h2>
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="md:w-1/3">
                        <img src="{{ asset('storage/' . $pemesanan->gedung->gambar) }}" alt="{{ $pemesanan->gedung->nama }}" 
                            class="w-full h-48 object-cover rounded-lg">
                    </div>
                    <div class="md:w-2/3">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $pemesanan->gedung->nama }}</h3>
                        <p class="text-gray-600 mb-4">{{ $pemesanan->gedung->lokasi }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-gray-600">Kapasitas</p>
                                <p class="font-medium">{{ $pemesanan->gedung->kapasitas }} Orang</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Harga per Jam</p>
                                <p class="font-medium">Rp {{ number_format($pemesanan->gedung->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-600">Fasilitas</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach(explode(',', $pemesanan->gedung->fasilitas) as $fasilitas)
                                <span class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">{{ trim($fasilitas) }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            @if($pemesanan->pembayaran)
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">Informasi Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Metode Pembayaran</p>
                        <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $pemesanan->pembayaran->metode_pembayaran)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status Pembayaran</p>
                        <p class="font-medium">{{ ucfirst($pemesanan->pembayaran->status) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Referensi Pembayaran</p>
                        <p class="font-medium">{{ $pemesanan->pembayaran->referensi_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Waktu Pembayaran</p>
                        <p class="font-medium">{{ $pemesanan->pembayaran->waktu_pembayaran->format('d F Y H:i') }}</p>
                    </div>
                </div>
                
                @if($pemesanan->pembayaran->bukti_pembayaran)
                <div class="mt-4">
                    <p class="text-gray-600 mb-2">Bukti Pembayaran</p>
                    <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" 
                        alt="Bukti Pembayaran" class="h-40 rounded-lg border">
                </div>
                @endif
            </div>
            @elseif($pemesanan->status === 'menunggu_pembayaran')
            <div class="p-6 bg-yellow-50">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-medium text-yellow-800">Menunggu Pembayaran</h3>
                        <p class="text-yellow-600">Silakan selesaikan pembayaran untuk mengkonfirmasi pemesanan Anda</p>
                    </div>
                    <a href="{{ route('pembayaran.show', $pemesanan->id_pemesanan) }}" 
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium">
                        Lanjutkan Pembayaran
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('beranda') }}" 
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center">
                Kembali ke Beranda
            </a>
            
            @if($pemesanan->status === 'menunggu_pembayaran')
            <form action="{{ route('pemesanan.batal', $pemesanan->id_pemesanan) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg font-medium"
                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pemesanan ini?')">
                    Batalkan Pemesanan
                </button>
            </form>
            @endif
            
            @if($pemesanan->status === 'dibayar')
            <a href="#" 
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-center">
                Cetak Invoice
            </a>
            @endif
        </div>
    </div>
</div>
@endsection