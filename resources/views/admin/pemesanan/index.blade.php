@extends('layouts.admin')

@section('title', 'Manajemen Pemesanan')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pemesanan</h6>
            <div class="dropdown no-arrow">
                <div>
                    <!-- Button trigger modal filter -->
                    <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        <i class="fas fa-filter me-1"></i> Filter Laporan
                    </button>

                    <!-- Button generate report -->
                    <a href="{{ route('admin.pemesanan.report', [
                        'status' => request('status'),
                        'gedung_id' => request('gedung_id'),
                        'user_id' => request('user_id'),
                        'start_date' => request('start_date'),
                        'end_date' => request('end_date'),
                    ]) }}"
                        class="btn btn-sm btn-outline-danger ms-2" title="Generate Report">
                        <i class="fas fa-file-pdf me-1"></i> Report
                    </a>
                </div>
            </div>

            <!-- Modal Filter -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.pemesanan.index') }}" method="GET">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Rentang Tanggal</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                    <input type="date" class="form-control mt-2" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ruangan</label>
                                    <select class="form-select" name="gedung_id">
                                        <option value="">Semua Ruangan</option>
                                        @foreach ($gedungs as $gedung)
                                            <option value="{{ $gedung->id_gedung }}"
                                                {{ request('gedung_id') == $gedung->id_gedung ? 'selected' : '' }}>
                                                {{ $gedung->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Pemesan</label>
                                    <select class="form-select" name="user_id">
                                        <option value="">Semua Pemesan</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-secondary">Reset</a>
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dropdown Filter Status -->
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton">
                <li>
                    <h6 class="dropdown-header">Filter Status</h6>
                </li>
                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => '']) }}">Semua</a></li>
                @foreach (['menunggu_pembayaran', 'deposit', 'dibayar', 'dikonfirmasi', 'selesai', 'dibatalkan'] as $status)
                    <li>
                        <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['status' => $status]) }}">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                            @if (request('status') == $status)
                                <i class="fas fa-check ms-2"></i>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        @if (request()->hasAny(['status', 'gedung_id', 'user_id', 'start_date']))
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <strong>Filter Aktif:</strong>
                @if (request('status'))
                    <span class="badge bg-primary me-2">
                        Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                    </span>
                @endif
                @if (request('gedung_id'))
                    <span class="badge bg-primary me-2">
                        Ruangan: {{ $gedungs->firstWhere('id_gedung', request('gedung_id'))->nama }}
                    </span>
                @endif
                @if (request('user_id'))
                    <span class="badge bg-primary me-2">
                        Pemesan: {{ $users->firstWhere('id', request('user_id'))->name }}
                    </span>
                @endif
                @if (request('start_date') && request('end_date'))
                    <span class="badge bg-primary me-2">
                        Tanggal: {{ date('d/m/Y', strtotime(request('start_date'))) }} -
                        {{ date('d/m/Y', strtotime(request('end_date'))) }}
                    </span>
                @endif
                <a href="{{ route('admin.pemesanan.index') }}" class="btn-close"></a>
            </div>
        @endif
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
                    @foreach ($pemesanan as $item)
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

                                    @if ($item->status === 'dibayar')
                                        <form action="{{ route('admin.pemesanan.confirm', $item->id_pemesanan) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-success"
                                                title="Konfirmasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->status === 'dikonfirmasi')
                                        <form action="{{ route('admin.pemesanan.complete', $item->id_pemesanan) }}"
                                            method="POST" class="me-2">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-success"
                                                title="Tandai Selesai">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if ($item->status === 'deposit')
                                        <form action="{{ route('admin.pemesanan.send-reminder', $item->id_pemesanan) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-outline-warning"
                                                title="Kirim Pengingat">
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

    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                },
                order: [
                    [2, 'desc']
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize date range picker
            $('.date-range-picker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'Pilih',
                    cancelLabel: 'Batal',
                    fromLabel: 'Dari',
                    toLabel: 'Sampai',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ],
                    firstDay: 1
                },
                opens: 'right',
                autoUpdateInput: false
            });

            $('.date-range-picker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            // Generate report with filters
            $('#generateReportBtn').click(function(e) {
                e.preventDefault();

                // Get all filter values
                const dateRange = $('#date_range').val();
                const gedungId = $('#gedung_id').val();
                const userId = $('#user_id').val();
                const status = $('#status').val();

                // Construct URL with query parameters
                let url = "{{ route('admin.pemesanan.report') }}?";

                if (dateRange) {
                    const dates = dateRange.split(' - ');
                    url += `start_date=${dates[0]}&end_date=${dates[1]}&`;
                }

                if (gedungId) url += `gedung_id=${gedungId}&`;
                if (userId) url += `user_id=${userId}&`;
                if (status) url += `status=${status}`;

                // Remove trailing & or ?
                url = url.replace(/[&?]$/, '');

                // Open report in new tab
                window.open(url, '_blank');
            });
        });
    </script>
@endpush
