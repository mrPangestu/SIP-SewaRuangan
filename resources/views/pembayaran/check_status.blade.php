@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Status Pembayaran - 
                    @if($pembayaran->jenis_pembayaran === 'deposit')
                        Deposit
                    @else
                        Pelunasan
                    @endif
                </div>

                <div class="card-body">
                    @if($pembayaran->status === 'completed')
                        <div class="alert alert-success">
                            <h4><i class="fas fa-check-circle"></i> Pembayaran Berhasil!</h4>
                            
                            <div class="payment-details mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Referensi:</strong></p>
                                        <p>{{ $pembayaran->referensi_pembayaran }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Metode Pembayaran:</strong></p>
                                        <p>{{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</p>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p><strong>Jumlah:</strong></p>
                                        <p>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Waktu Pembayaran:</strong></p>
                                        <p>{{ $pembayaran->waktu_pembayaran->format('d M Y H:i:s') }}</p>
                                    </div>
                                </div>
                                
                                @if($pembayaran->va_number)
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <p><strong>Nomor Virtual Account:</strong></p>
                                            <p class="text-primary">{{ $pembayaran->va_number }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <hr>
                            
                            @if($pembayaran->jenis_pembayaran === 'deposit')
                                <div class="next-steps mt-4">
                                    <h5>Langkah Selanjutnya:</h5>
                                    <ol>
                                        <li>Pembayaran deposit Anda telah berhasil</li>
                                        <li>Silakan lakukan pembayaran pelunasan sebelum 
                                            <strong>{{ $pembayaran->pemesanan->tanggal_mulai->subDays(7)->format('d M Y') }}</strong>
                                        </li>
                                        <li>Anda akan menerima email konfirmasi deposit</li>
                                    </ol>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="{{ route('pemesanan.show', $pembayaran->id_pemesanan) }}" 
                                       class="btn btn-success">
                                        Kembali ke Detail Pemesanan
                                    </a>
                                </div>
                            @else
                                <div class="next-steps mt-4">
                                    <h5>Pembayaran Telah Dilunasi!</h5>
                                    <p>Terima kasih telah menyelesaikan pembayaran. Berikut langkah selanjutnya:</p>
                                    <ol>
                                        <li>Anda akan menerima invoice lengkap via email</li>
                                        <li>Tunjukkan bukti pembayaran saat check-in di lokasi</li>
                                        <li>Hubungi kami jika ada pertanyaan</li>
                                    </ol>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <a href="{{ route('pembayaran.success', $pembayaran->id_pembayaran) }}" 
                                       class="btn btn-success mr-2">
                                        Lihat Detail Pembayaran
                                    </a>
                                    <a href="{{ route('invoice.download', $pembayaran->id_pembayaran) }}" 
                                       class="btn btn-primary">
                                        Download Invoice
                                    </a>
                                </div>
                            @endif
                        </div>
                    @elseif($pembayaran->status === 'pending')
                        <div class="alert alert-warning">
                            <h4><i class="fas fa-clock"></i> Menunggu Pembayaran</h4>
                            <p>Silakan selesaikan pembayaran Anda</p>
                            
                            @if(in_array($pembayaran->metode_pembayaran, ['bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'mandiri_va']))
                                <div class="bank-instruction mt-4">
                                    <h5>Instruksi Transfer:</h5>
                                    <ol>
                                        <li>Buka aplikasi mobile banking atau ATM</li>
                                        <li>Pilih menu Transfer</li>
                                        @if($pembayaran->va_number)
                                            <li>Masukkan nomor VA: <strong>{{ $pembayaran->va_number }}</strong></li>
                                        @endif
                                        <li>Masukkan jumlah: <strong>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</strong></li>
                                        <li>Konfirmasi pembayaran</li>
                                    </ol>
                                    
                                    @if($pembayaran->metode_pembayaran === 'mandiri_va')
                                        <div class="alert alert-info mt-3">
                                            <p>Khusus Mandiri: Gunakan kode pembayaran <strong>70012</strong> + kode perusahaan</p>
                                        </div>
                                    @endif
                                </div>
                            @elseif($pembayaran->metode_pembayaran === 'gopay')
                                <div class="ewallet-instruction mt-4">
                                    <h5>Instruksi Gopay:</h5>
                                    <ol>
                                        <li>Buka aplikasi Gojek</li>
                                        <li>Pilih menu Bayar</li>
                                        <li>Scan QR code yang ditampilkan</li>
                                        <li>Masukkan PIN untuk menyelesaikan pembayaran</li>
                                    </ol>
                                </div>
                            @elseif($pembayaran->metode_pembayaran === 'qris')
                                <div class="qris-instruction mt-4">
                                    <h5>Instruksi QRIS:</h5>
                                    <ol>
                                        <li>Buka aplikasi e-wallet atau mobile banking</li>
                                        <li>Pilih menu Scan QR</li>
                                        <li>Arahkan kamera ke QR code</li>
                                        <li>Konfirmasi pembayaran</li>
                                    </ol>
                                </div>
                            @endif
                            
                            <div class="payment-info mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Referensi:</strong></p>
                                        <p>{{ $pembayaran->referensi_pembayaran }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Jumlah:</strong></p>
                                        <p>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                @if($pembayaran->jenis_pembayaran === 'pelunasan')
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <p>Batas waktu pelunasan: 
                                                    <strong>{{ $pembayaran->pemesanan->tanggal_mulai->subDays(7)->format('d M Y') }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="{{ $retryUrl }}" class="btn btn-primary mr-2">
                                <i class="fas fa-sync-alt"></i> Periksa Status
                            </a>
                            
                            @if($pembayaran->metode_pembayaran === 'bank_transfer' && $pembayaran->va_number)
                                <a href="{{ $pembayaran->bukti_pembayaran ?? '#' }}" 
                                   class="btn btn-secondary" 
                                   target="_blank"
                                   @if(!$pembayaran->bukti_pembayaran) disabled @endif>
                                    <i class="fas fa-file-pdf"></i> Lihat Instruksi Pembayaran
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4><i class="fas fa-times-circle"></i> Pembayaran Gagal</h4>
                            <p>Status: {{ ucfirst($pembayaran->status) }}</p>
                            
                            @if($pembayaran->status === 'expired')
                                <p class="mt-3">Waktu pembayaran telah habis. Silakan lakukan pemesanan ulang.</p>
                            @endif
                        </div>
                        
                        <div class="payment-info mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Referensi:</strong></p>
                                    <p>{{ $pembayaran->referensi_pembayaran }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Jumlah:</strong></p>
                                    <p>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            @if($pembayaran->jenis_pembayaran === 'deposit')
                                <a href="{{ route('pembayaran.deposit', $pembayaran->id_pemesanan) }}" 
                                   class="btn btn-warning mr-2">
                                    Coba Bayar Lagi
                                </a>
                            @else
                                <a href="{{ route('pembayaran.pelunasan', $pembayaran->id_pemesanan) }}" 
                                   class="btn btn-warning mr-2">
                                    Coba Bayar Lagi
                                </a>
                            @endif
                            
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
    // Auto refresh setiap 10 detik untuk pembayaran pending
    setTimeout(function() {
        window.location.href = "{{ $retryUrl }}";
    }, 10000);
</script>
@endif

<style>
    .payment-details {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .next-steps {
        background-color: #e8f5e9;
        padding: 15px;
        border-radius: 5px;
    }
    .bank-instruction, .ewallet-instruction, .qris-instruction {
        background-color: #fff8e1;
        padding: 15px;
        border-radius: 5px;
    }
</style>
@endsection