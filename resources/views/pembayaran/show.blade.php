@extends('app')

@section('title', 'Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
                <p class="text-gray-600">ID Pemesanan: {{ $pemesanan->id_pemesanan }}</p>
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
                            <p class="text-gray-600">Tanggal</p>
                            <p class="font-medium">
                                {{ $pemesanan->tanggal_mulai->format('d F Y H:i') }} - 
                                {{ $pemesanan->tanggal_selesai->format('H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-gray-600">Nama Acara</p>
                            <p class="font-medium">{{ $pemesanan->nama_acara }}</p>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <p class="text-gray-600">Total Pembayaran</p>
                            <p class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Metode Pembayaran -->
                <div class="md:w-1/2 p-6">
                    <h2 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h2>
                    
                    <form action="{{ route('pembayaran.proses') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_pemesanan" value="{{ $pemesanan->id_pemesanan }}">
                        
                        <div class="space-y-3 mb-6">
                            @foreach($paymentMethods as $code => $method)
                            <div class="payment-method">
                                <input type="radio" name="metode_pembayaran" 
                                    id="method-{{ $code }}" value="{{ $code }}" 
                                    class="hidden peer" required>
                                <label for="method-{{ $code }}" 
                                    class="flex items-center p-4 border rounded-lg cursor-pointer
                                    hover:border-blue-400 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                    <img src="{{ $method['logo'] }}" alt="{{ $method['nama'] }}" class="h-8 mr-3">
                                    <div>
                                        <p class="font-medium">{{ $method['nama'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $method['deskripsi'] }}</p>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const button = document.getElementById('payButton');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
});
</script>
@endpush