@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran - {{ $pembayaran->metode_pembayaran }}</div>

                <div class="card-body">
                    <div class="alert alert-primary">
                        <h5>Instruksi Pembayaran:</h5>
                        <ul>
                            @foreach($paymentInstructions as $instruction)
                                <li>{{ $instruction }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <p>Nomor Pesanan: <strong>{{ $pembayaran->referensi_pembayaran }}</strong></p>
                        <p>Total Pembayaran: <strong>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</strong></p>
                    </div>

                    <div id="snap-container" class="mb-4"></div>

                    <div class="text-center">
                        <a href="{{ route('pemesanan.show', $pembayaran->id_pemesanan) }}" 
                           class="btn btn-secondary">
                            Batalkan Pembayaran
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = "{{ route('pembayaran.check-status', $pembayaran->id_pembayaran) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('pembayaran.check-status', $pembayaran->id_pembayaran) }}";
            },
            onError: function(result) {
                window.location.href = "{{ route('pembayaran.check-status', $pembayaran->id_pembayaran) }}";
            },
            onClose: function() {
                // Tetap biarkan user menutup popup tanpa melakukan apa-apa
            }
        });
    });
</script>
@endsection