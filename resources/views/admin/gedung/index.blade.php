@extends('layouts.admin')

@section('title', 'Manajemen Gedung')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Gedung</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGedungModal">
                <i class="fas fa-plus me-1"></i> Tambah Gedung
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Harga/Jam</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gedungs as $gedung)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $gedung->nama }}</td>
                            <td>{{ $gedung->kategori->nama_kategori }}</td>
                            <td>{{ $gedung->daerah }}</td>
                            <td>Rp {{ number_format($gedung->harga, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white" 
                                    data-bs-toggle="modal" data-bs-target="#editGedungModal{{ $gedung->id_gedung }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteGedungModal{{ $gedung->id_gedung }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Gedung Modal -->
                        <div class="modal fade" id="editGedungModal{{ $gedung->id_gedung }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Gedung</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.gedung.update', $gedung->id_gedung) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="nama" class="form-label">Nama Gedung</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" 
                                                        value="{{ $gedung->nama }}" required maxlength="30">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="id_kategori" class="form-label">Kategori</label>
                                                    <select class="form-select" id="id_kategori" name="id_kategori" required>
                                                        @foreach($kategories as $kategori)
                                                        <option value="{{ $kategori->id_kategori }}" 
                                                            {{ $gedung->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                                                            {{ $kategori->nama_kategori }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="lokasi" class="form-label">Lokasi</label>
                                                    <input type="text" class="form-control" id="lokasi" name="lokasi" 
                                                        value="{{ $gedung->lokasi }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="daerah" class="form-label">Daerah</label>
                                                    <select class="form-select" id="daerah" name="daerah" required>
                                                        @foreach($daerahOptions as $daerah)
                                                        <option value="{{ $daerah }}" 
                                                            {{ $gedung->daerah == $daerah ? 'selected' : '' }}>
                                                            {{ ucwords($daerah) }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="kapasitas" class="form-label">Kapasitas</label>
                                                    <input type="number" class="form-control" id="kapasitas" name="kapasitas" 
                                                        value="{{ $gedung->kapasitas }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="harga" class="form-label">Harga per Jam</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control" id="harga" name="harga" 
                                                            value="{{ $gedung->harga }}" step="0.01" min="0" max="9999999.99" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fasilitas" class="form-label">Fasilitas</label>
                                                <textarea class="form-control" id="fasilitas" name="fasilitas" rows="2" required>{{ $gedung->fasilitas }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ $gedung->deskripsi }}</textarea>
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

                        <!-- Delete Gedung Modal -->
                        <div class="modal fade" id="deleteGedungModal{{ $gedung->id_gedung }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Gedung</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.gedung.destroy', $gedung->id_gedung) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus gedung <strong>{{ $gedung->nama }}</strong>?</p>
                                            <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
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

<!-- Create Gedung Modal -->
<div class="modal fade" id="createGedungModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gedung Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.gedung.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Gedung</label>
                            <input type="text" class="form-control" id="nama" name="nama" required maxlength="30">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="id_kategori" name="id_kategori" required>
                                @foreach($kategories as $kategori)
                                <option value="{{ $kategori->id_kategori }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="daerah" class="form-label">Daerah</label>
                            <select class="form-select" id="daerah" name="daerah" required>
                                @foreach($daerahOptions as $daerah)
                                <option value="{{ $daerah }}">{{ ucwords($daerah) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kapasitas" class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" id="kapasitas" name="kapasitas" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga per Jam</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="harga" name="harga" 
                                    step="0.01" min="0" max="9999999.99" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fasilitas" class="form-label">Fasilitas</label>
                        <textarea class="form-control" id="fasilitas" name="fasilitas" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
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
    .input-group-text {
        background-color: #e9ecef;
    }
    .form-select, .form-control {
        border-radius: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Format input harga
    document.querySelectorAll('input[name="harga"]').forEach(input => {
        input.addEventListener('change', function() {
            this.value = parseFloat(this.value).toFixed(2);
        });
    });
</script>
@endsection