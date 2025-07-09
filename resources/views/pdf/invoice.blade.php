<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $pembayaran->referensi_pembayaran }}</title>
    <style type="text/css">
        /* Gunakan font system default yang bagus untuk PDF */
        body { 
            font-family: Helvetica, Arial, sans-serif;
            color: #1a202c;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        
        .page {
            max-width: 800px;
            margin: 0 auto;
            padding: 1.5cm;
        }
        
        /* Header Styles */
        .header {
            border-bottom: 8px solid #2a4365;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2a4365;
            margin: 0 0 5px 0;
        }
        
        .invoice-number {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 12px;
            color: #4a5568;
        }
        
        /* Info Section */
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-block {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 15px;
        }
        
        .info-block-title {
            font-weight: bold;
            color: #2a4365;
            margin-bottom: 10px;
            font-size: 13px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        
        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .table th {
            background-color: #2a4365;
            color: white;
            font-weight: 500;
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
        }
        
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            background-color: #2a4365 !important;
            color: white;
            font-weight: bold;
        }
        
        .total-row td {
            border: none !important;
            padding: 12px;
        }
        
        /* Payment Info */
        .payment-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 4px;
            margin-top: 30px;
        }
        
        .payment-info-item {
            margin-bottom: 8px;
        }
        
        .payment-info-label {
            font-weight: bold;
            color: #2a4365;
            display: inline-block;
            width: 150px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            background-color: #48bb78;
            color: white;
            font-weight: bold;
            font-size: 11px;
            border-radius: 3px;
        }
        
        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #718096;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .thank-you {
            font-weight: bold;
            color: #2a4365;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        /* Utility Classes */
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1 class="invoice-title">INVOICE</h1>
            <div class="invoice-number">No: {{ $pembayaran->referensi_pembayaran }}</div>
            <div class="invoice-date">Date: {{ $pembayaran->waktu_pembayaran->format('d F Y') }}</div>
        </div>
        
        <div class="info-section">
            <div class="info-block">
                <div class="info-block-title">Bill To</div>
                <div>
                    <strong>{{ $user->name }}</strong><br>
                    {{ $user->email }}<br>
                    {{ $user->phone ?? '-' }}<br>
                </div>
            </div>
            
            <div class="info-block">
                <div class="info-block-title">From</div>
                <div>
                    <strong>Vanuefy</strong><br>
                    +62 812-3456-789<br>
                    support@vanuefy.com
                </div>
            </div>
        </div>
        
        <div class="info-block-title mb-20">Order Details</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date & Time</th>
                    <th>Duration</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $gedung->nama }}</strong><br>
                        <small>{{ $gedung->lokasi }}</small><br>
                        <em>Event: {{ $pemesanan->nama_acara }}</em>
                    </td>
                    <td>
                        {{ $pemesanan->tanggal_mulai->format('d F Y') }}<br>
                        <small>{{ $pemesanan->tanggal_mulai->format('H:i') }} - {{ $pemesanan->tanggal_selesai->format('H:i') }}</small>
                    </td>
                    <td>{{ $pemesanan->tanggal_mulai->diffInHours($pemesanan->tanggal_selesai) }} hours</td>
                    <td class="text-right">Rp {{ number_format($gedung->harga, 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total Amount</td>
                    <td class="text-right">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="payment-info">
            <div class="info-block-title mb-20">Payment Information</div>
            <div class="payment-info-item">
                <span class="payment-info-label">Payment Method:</span>
                {{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}
            </div>
            <div class="payment-info-item">
                <span class="payment-info-label">Status:</span>
                <span class="status-badge">{{ ucfirst($pembayaran->status) }}</span>
            </div>
            <div class="payment-info-item">
                <span class="payment-info-label">Payment Date:</span>
                {{ $pembayaran->waktu_pembayaran->format('d F Y H:i') }}
            </div>
        </div>
        
        <div class="footer">
            <div class="thank-you">Thank you for your business!</div>
            <p>If you have any questions, please contact us at <a href="mailto:support@venuefy.com" style="color: #6e48aa;">support@venuefy.com</a></p>
            <p>© {{ date('Y') }} Venuefy. All rights reserved.</p>
        </div>
    </div>
</body>
</html>