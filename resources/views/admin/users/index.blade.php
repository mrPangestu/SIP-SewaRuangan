@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
        <div class="d-flex">
            <form class="d-none d-sm-inline-block form-inline mr-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-white border-0 small" 
                        placeholder="Cari pengguna..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Total Pemesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->total_pemesanan ?? 0 }}</td>
                        
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                    class="btn btn-sm btn-icon btn-outline-primary mr-2" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" 
                                        title="Hapus" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data pengguna</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>
@endsection