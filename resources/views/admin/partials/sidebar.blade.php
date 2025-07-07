<div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white py-4">
        <h4 class="mb-0"><i class="fas fa-building me-2"></i>Venuefy</h4>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-dark text-white">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.pemesanan.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
            <i class="fas fa-calendar-check me-2"></i> Pemesanan
        </a>
        <a href="{{ route('admin.gedung.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
            <i class="fas fa-building me-2"></i> Gedung
        </a>
        <a href="{{ route('admin.kategori.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
            <i class="fas fa-tags me-2"></i> Kategori
        </a>
        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
            <i class="fas fa-users me-2"></i> Pengguna
        </a>
    </div>
</div>