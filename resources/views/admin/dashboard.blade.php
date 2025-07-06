@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Toast Container -->
<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11"></div>

<!-- Flash Messages -->
@if(session('success'))
<div class="flash-message d-none" data-type="success">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash-message d-none" data-type="danger">{{ session('error') }}</div>
@endif

<div class="row mb-4">
    <!-- Stats Card 1 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pemesanan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPemesanan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card 2 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Gedung Tersedia</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalGedung }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card 3 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Card 4 -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Pemesanan -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pemesanan Terbaru</h6>
                <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Gedung</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPemesanan as $pemesanan)
                            <tr>
                                <td>{{ $pemesanan->gedung->nama }}</td>
                                <td>{{ $pemesanan->tanggal_mulai->format('d M Y') }}</td>
                                <td>
                                    <span>
                                        <span class="badge bg-{{ $pemesanan->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.pemesanan.show', $pemesanan->id_pemesanan) }}" 
                                        class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="tooltip" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Distribusi Status</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="statusPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">

                    @foreach ($statusData as $status => $jumlah)
                        <span class="badge bg-{{ $statusColorMapping[$status] ?? 'secondary' }}">
                            {{ $status }} ({{ $jumlah }})
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Status Pie Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statusPieChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($statusData)),
            datasets: [{
                data: @json(array_values($statusData)),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#2c9faf',
                    '#dda20a',
                    '#be2617'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    padding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush