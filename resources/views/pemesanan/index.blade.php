@extends('layouts.app')

@section('title', 'Daftar Pemesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Pemesanan Saya</h1>
            <a href="{{ route('beranda') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
            </a>
        </div>

        @if($pemesanans->isEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <img src="{{ asset('images/empty-order.png') }}" alt="No orders" class="h-40 mx-auto mb-4">
            <h3 class="text-lg font-medium text-gray-700 mb-2">Anda belum memiliki pemesanan</h3>
            <p class="text-gray-500 mb-4">Silakan melakukan pemesanan gedung terlebih dahulu</p>
            <a href="{{ route('beranda') }}" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Cari Gedung
            </a>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Filter -->
            <div class="p-4 border-b flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <label for="status-filter" class="mr-2 text-gray-600">Filter Status:</label>
                    <select id="status-filter" class="border rounded-lg px-3 py-1">
                        <option value="all">Semua</option>
                        <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                        <option value="dibayar">Dibayar</option>
                        <option value="dikonfirmasi">Dikonfirmasi</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $pemesanans->firstItem() }} - {{ $pemesanans->lastItem() }} dari {{ $pemesanans->total() }} pemesanan
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID Pemesanan
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Gedung
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pemesanans as $pemesanan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    #{{ substr($pemesanan->id_pemesanan, 0, 8) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $pemesanan->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" 
                                            src="{{ asset('storage/' . $pemesanan->gedung->gambar) }}" 
                                            alt="{{ $pemesanan->gedung->nama }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $pemesanan->gedung->nama }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $pemesanan->gedung->lokasi }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $pemesanan->tanggal_mulai->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $pemesanan->tanggal_mulai->format('H:i') }} - {{ $pemesanan->tanggal_selesai->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($pemesanan->status === 'dibayar') bg-green-100 text-green-800
                                    @elseif($pemesanan->status === 'menunggu_pembayaran') bg-yellow-100 text-yellow-800
                                    @elseif($pemesanan->status === 'dibatalkan') bg-red-100 text-red-800
                                    @elseif($pemesanan->status === 'dikonfirmasi') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('pemesanan.show', $pemesanan->id_pemesanan) }}" 
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if($pemesanan->status === 'menunggu_pembayaran')
                                <a href="{{ route('pembayaran.show', $pemesanan->id_pemesanan) }}" 
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-credit-card"></i> Bayar
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $pemesanans->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    
    statusFilter.addEventListener('change', function() {
        const status = this.value;
        const url = new URL(window.location.href);
        
        if (status === 'all') {
            url.searchParams.delete('status');
        } else {
            url.searchParams.set('status', status);
        }
        
        window.location.href = url.toString();
    });

    // Set selected filter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');
    if (statusParam) {
        statusFilter.value = statusParam;
    }
});
</script>
@endpush