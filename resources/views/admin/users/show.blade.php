@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <img src="/img/profile.png" class="m-3" alt="profil" width="100">

                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pengguna</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-5 text-muted">ID Pengguna:</div>
                    <div class="col-7">{{ $user->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-5 text-muted">No. Telepon:</div>
                    <div class="col-7">{{ $user->phone ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-5 text-muted">Terdaftar Pada:</div>
                    <div class="col-7">{{ $user->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-5 text-muted">Total Pemesanan:</div>
                    <div class="col-7">{{ $user->total_pemesanan }}</div>
                </div>
                <div class="row">
                    <div class="col-5 text-muted">Pemesanan Aktif:</div>
                    <div class="col-7">{{ $user->pemesanan_aktif }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Pemesanan Terakhir</h6>
                <a href="{{ route('admin.pemesanan.index', ['user_id' => $user->id]) }}" 
                    class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                @if($pemesanan->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">Belum ada riwayat pemesanan</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gedung</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pemesanan as $item)
                            <tr>
                                <td>{{ $item->id_pemesanan }}</td>
                                <td>{{ $item->gedung->nama }}</td>
                                <td>{{ $item->tanggal_mulai->format('d M Y') }}</td>
                                <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pemesanan.show', $item->id_pemesanan) }}" 
                                        class="btn btn-sm btn-icon btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection