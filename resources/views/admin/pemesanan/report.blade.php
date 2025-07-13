<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $title }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .logo {
            height: 70px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }

        .subtitle {
            font-size: 12pt;
            color: #555;
            margin-bottom: 5px;
        }

        .meta {
            font-size: 9pt;
            color: #666;
            margin-bottom: 15px;
        }

        .filter-info {
            margin-bottom: 15px;
            font-size: 9pt;
            background: #f5f5f5;
            padding: 8px;
            border-radius: 4px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            page-break-inside: auto;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
            padding: 6px;
            border: 1px solid #ddd;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Status Badges */
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9pt;
            font-weight: bold;
            display: inline-block;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-primary {
            background-color: #007bff;
            color: #fff;
        }

        .badge-info {
            background-color: #17a2b8;
            color: #fff;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #fff;
        }

        /* Summary Sections */
        .summary-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .summary-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        @if (file_exists($logo))
            <img src="{{ $logo }}" class="logo" alt="Logo">
        @endif
        <div class="title">{{ $title }}</div>
        <div class="subtitle">Periode: {{ $filter['date_range'] }}</div>
        <div class="meta">
            Dicetak pada: {{ $report_date }} | Halaman: <span class="page"></span> dari <span class="topage"></span>
        </div>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <strong>Filter yang digunakan:</strong><br>
        Ruangan: {{ $filter['gedung'] }} |
        Pemesan: {{ $filter['user'] }} |
        Status: {{ $filter['status'] }}
    </div>

    <!-- Main Data Table -->
    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">ID Pemesanan</th>
                <th width="15%">Ruangan</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Pemesan</th>
                <th width="10%">Durasi</th>
                <th width="12%" class="text-right">Total</th>
                <th width="8%">Status</th>
                <th width="15%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemesanan as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->id_pemesanan }}</td>
                    <td>{{ $item->gedung->nama }}</td>
                    <td>{{ $item->tanggal_mulai->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-center">{{ $item->durasi }} jam</td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $item->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>Total Pendapatan:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <!-- Frequent Bookers Section -->
    <div class="summary-section">
        <div class="summary-title">5 Pemesan Teraktif</div>
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Nama Pemesan</th>
                    <th width="20%">Email</th>
                    <th width="15%">No. Telepon</th>
                    <th width="10%">Jumlah Pesanan</th>
                    <th width="25%">Terakhir Memesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($frequent_bookers as $booker)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $booker->name }}</td>
                        <td>{{ $booker->email }}</td>
                        <td>{{ $booker->phone ?? '-' }}</td>
                        <td class="text-center">{{ $booker->pemesanan_count }}</td>
                        <td>
                            @if ($booker->pemesanan_count > 0)
                                {{ $booker->pemesanan->sortByDesc('created_at')->first()->created_at->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Venuefy &copy; {{ date('Y') }} | Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan
    </div>

    <!-- Page numbering script -->
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 15;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>

</html>
