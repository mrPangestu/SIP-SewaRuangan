@extends('layouts.admin')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Detail Pemesanan #{{ $pemesanan->id_pemesanan }}</h6>
        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Informasi Pemesanan</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Status</th>
                        <td>
                            <span class="badge bg-{{ $pemesanan->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Gedung</th>
                        <td>{{ $pemesanan->gedung->nama }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Acara</th>
                        <td>    {{ $pemesanan->tanggal_mulai ? $pemesanan->tanggal_mulai->format('d M Y H:i') : '-' }} - 
                                {{ $pemesanan->tanggal_selesai ? $pemesanan->tanggal_selesai->format('H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Nama Acara</th>
                        <td>{{ $pemesanan->nama_acara }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Informasi Pembayaran</h5>
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Total Harga</th>
                        <td>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Deposit (20%)</th>
                        <td>Rp {{ $pemesanan->deposit_amount ? $pemesanan->deposit_amount : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Pembayaran</th>
                        <td>Rp {{ $pemesanan->remaining_amount ? $pemesanan->remaining_amount : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Deposit</th>
                        <td>{{ $pemesanan->deposit_paid_at ? $pemesanan->deposit_paid_at : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Pelunasan</th>
                        <td>{{ $pemesanan->full_payment_paid_at ? $pemesanan->full_payment_paid_at : '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
    {{-- <h5>Riwayat Pembayaran</h5> --}}
    <div class="table-responsive">
        {{-- <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pembayaran</th>
                    <th>Jenis</th>
                    <th>Metode</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @if($pemesanan->pembayaran && $pemesanan->pembayaran->count() > 0)
                    @foreach($pemesanan->pembayaran as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran->id_pembayaran }}</td>
                        <td>{{ ucfirst($pembayaran->jenis_pembayaran) }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</td>
                        <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $pembayaran->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($pembayaran->status) }}
                            </span>
                        </td>
                        <td>{{ $pembayaran->waktu_pembayaran ? \Carbon\Carbon::parse($pembayaran->waktu_pembayaran)->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada riwayat pembayaran</td>
                    </tr>
                @endif
            </tbody>
        </table> --}}
    </div>
</div>

        @if(in_array($pemesanan->status, ['dibayar', 'dikonfirmasi']))
        <div class="mt-4 text-end">
            <form action="{{ route('admin.pemesanan.complete', $pemesanan->id_pemesanan) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check-double"></i> Tandai Sebagai Selesai
                </button>
            </form>
        </div>
        @endif

        @if($pemesanan->status === 'deposit')
        <div class="mt-4 text-end">
            <form action="{{ route('admin.pemesanan.send-reminder', $pemesanan->id_pemesanan) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-bell"></i> Kirim Pengingat Pelunasan
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection