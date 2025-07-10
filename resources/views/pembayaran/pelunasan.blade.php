@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pembayaran Pelunasan</div>

                <div class="card-body">
                    <h5 class="mb-4">Detail Pemesanan</h5>
                    
                    <div class="mb-4">
                        <p>Gedung: <strong>{{ $pemesanan->gedung->nama_gedung }}</strong></p>
                        <p>Tanggal: <strong>{{ $pemesanan->tanggal_mulai->format('d M Y H:i') }}</strong> s/d 
                           <strong>{{ $pemesanan->tanggal_selesai->format('H:i') }}</strong></p>
                        <p>Total Harga: <strong>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong></p>
                        <p>Deposit (20%): <strong class="text-success">Rp {{ number_format($pemesanan->deposit_amount, 0, ',', '.') }} (Sudah dibayar)</strong></p>
                        <p class="text-primary">Sisa Pembayaran: <strong>Rp {{ number_format($pemesanan->remaining_amount, 0, ',', '.') }}</strong></p>
                    </div>

                    <form method="POST" action="{{ route('pembayaran.proses-pelunasan', $pemesanan->id_pemesanan) }}">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="metode_pembayaran">Pilih Metode Pembayaran</label>
                            <select class="form-control" id="metode_pembayaran" name="metode_pembayaran" required>
                                @foreach($paymentMethods as $key => $method)
                                    <option value="{{ $key }}">
                                        {{ $method['nama'] }} ({{ $method['deskripsi'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info">
                            <p>Silakan selesaikan pembayaran pelunasan sebelum acara dimulai.</p>
                            @if($pemesanan->tanggal_mulai->diffInDays(now()) < 7)
                                <p class="text-danger">Pembayaran pelunasan harus segera dilakukan karena acara akan segera dimulai.</p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            Lanjutkan Pembayaran Pelunasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection