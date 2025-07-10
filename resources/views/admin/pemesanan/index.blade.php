@extends('layouts.admin')

@section('title', 'Manajemen Pemesanan')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pemesanan</h6>
        <div class="dropdown no-arrow">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" 
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
                <li><h6 class="dropdown-header">Filter Status</h6></li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">Semua</a></li>
                @foreach(['menunggu_pembayaran', 'deposit', 'dibayar', 'dikonfirmasi', 'selesai', 'dibatalkan'] as $status)
                <li>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => $status]) }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gedung</th>
                        <th>Tanggal</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Deposit</th>
                        <th>Sisa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemesanan as $item)
                    <tr>
                        <td>{{ $item->id_pemesanan }}</td>
                        <td>{{ $item->gedung->nama }}</td>
                        <td>{{ $item->tanggal_mulai->format('d M Y H:i') }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->deposit_amount, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->remaining_amount, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.pemesanan.detail', $item->id_pemesanan) }}" 
                                    class="btn btn-sm btn-icon btn-outline-primary me-2" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($item->status === 'dibayar')
                                <form action="{{ route('admin.pemesanan.confirm', $item->id_pemesanan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-success" title="Konfirmasi">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($item->status ==='dikonfirmasi')
                                <form action="{{ route('admin.pemesanan.complete', $item->id_pemesanan) }}" method="POST" class="me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-success" title="Tandai Selesai">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($item->status === 'deposit')
                                <form action="{{ route('admin.pemesanan.send-reminder', $item->id_pemesanan) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" title="Kirim Pengingat">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('.datatable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        },
        order: [[2, 'desc']]
    });
});
</script>
@endpush