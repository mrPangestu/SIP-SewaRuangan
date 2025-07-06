@extends('layouts.admin')

@section('title', 'Manajemen Kategori Gedung')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kategori Gedung</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createKategoriModal">
                <i class="fas fa-plus me-1"></i> Tambah Kategori
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategories as $kategori)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>{{ $kategori->deskripsi ?? '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white" 
                                    data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $kategori->id_kategori }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteKategoriModal{{ $kategori->id_kategori }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Kategori Modal -->
                        <div class="modal fade" id="editKategoriModal{{ $kategori->id_kategori }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" 
                                                    value="{{ $kategori->nama_kategori }}" required maxlength="20">
                                            </div>
                                            <div class="mb-3">
                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                                    rows="3" maxlength="100">{{ $kategori->deskripsi }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Kategori Modal -->
                        <div class="modal fade" id="deleteKategoriModal{{ $kategori->id_kategori }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.kategori.destroy', $kategori->id_kategori) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $kategori->nama_kategori }}</strong>?</p>
                                            @if($kategori->gedung()->count() > 0)
                                            <div class="alert alert-warning">
                                                Kategori ini digunakan oleh {{ $kategori->gedung()->count() }} gedung. 
                                                Penghapusan akan dibatalkan jika kategori masih digunakan.
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Kategori Modal -->
<div class="modal fade" id="createKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" maxlength="100"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-radius: 10px 10px 0 0;
    }
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection