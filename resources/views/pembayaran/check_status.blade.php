<!-- resources/views/pembayaran/check_status.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Status Pembayaran - {{ $pembayaran->metode_pembayaran }}</div>

                <div class="card-body">
                    @if($pembayaran->status === 'completed')
                        <div class="alert alert-success">
                            <h4><i class="fas fa-check-circle"></i> Pembayaran Berhasil!</h4>
                            <p>Referensi: {{ $pembayaran->referensi_pembayaran }}</p>
                            @if($pembayaran->metode_pembayaran === 'bank_transfer')
                                <p>Nomor VA: {{ $pembayaran->va_number ?? '-' }}</p>
                            @endif
                            <p>Waktu: {{ $pembayaran->waktu_pembayaran->format('d M Y H:i') }}</p>
                        </div>
                        
                        <a href="{{ route('pembayaran.success', $pembayaran->id_pembayaran) }}" 
                           class="btn btn-success btn-block">
                            Lihat Detail Pembayaran
                        </a>
                        
                    @elseif($pembayaran->status === 'pending')
                        <div class="alert alert-warning">
                            <h4><i class="fas fa-clock"></i> Menunggu Pembayaran</h4>
                            <p>Silakan selesaikan pembayaran Anda</p>
                            
                            @if($pembayaran->metode_pembayaran === 'bank_transfer')
                                <div class="bank-instruction mt-3">
                                    <h5>Instruksi Transfer:</h5>
                                    <ol>
                                        <li>Buka aplikasi mobile banking atau ATM</li>
                                        <li>Pilih menu Transfer</li>
                                        <li>Masukkan nomor VA: <strong>{{ $pembayaran->va_number ?? '-' }}</strong></li>
                                        <li>Masukkan jumlah: Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</li>
                                        <li>Konfirmasi pembayaran</li>
                                    </ol>
                                </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ $retryUrl }}" class="btn btn-primary">
                                <i class="fas fa-sync-alt"></i> Periksa Status
                            </a>
                        </div>
                        
                    @else
                        <div class="alert alert-danger">
                            <h4><i class="fas fa-times-circle"></i> Pembayaran Gagal</h4>
                            <p>Status: {{ $pembayaran->status }}</p>
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('pembayaran.show', $pembayaran->id_pemesanan) }}" 
                               class="btn btn-warning mr-2">
                                Coba Metode Lain
                            </a>
                            <a href="{{ route('pemesanan.show', $pembayaran->id_pemesanan) }}" 
                               class="btn btn-secondary">
                                Kembali ke Pemesanan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($pembayaran->status === 'pending')
<script>
    // Auto refresh setiap 10 detik untuk pending payment
    setTimeout(function() {
        window.location.href = "{{ $retryUrl }}";
    }, 10000);
</script>
@endif
@endsection