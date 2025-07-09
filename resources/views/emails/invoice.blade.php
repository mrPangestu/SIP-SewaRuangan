@component('mail::message')
<style>
    .header {
        background: linear-gradient(135deg, #6e48aa 0%, #9d50bb 100%);
        padding: 30px 0;
        text-align: center;
        color: white;
        border-radius: 8px 8px 0 0;
        margin-bottom: 30px;
    }
    .invoice-number {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }
    .thank-you {
        font-size: 18px;
        opacity: 0.9;
    }
    .section-title {
        color: #6e48aa;
        font-size: 20px;
        font-weight: 600;
        margin: 25px 0 15px 0;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 8px;
    }
    .detail-item {
        display: flex;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    .detail-label {
        font-weight: 600;
        color: #555;
        min-width: 160px;
    }
    .detail-value {
        color: #333;
    }
    .highlight-box {
        background: #f9f5ff;
        border-left: 4px solid #9d50bb;
        padding: 15px;
        margin: 20px 0;
        border-radius: 0 4px 4px 0;
    }
    .button-container {
        text-align: center;
        margin: 30px 0;
    }
    .footer {
        margin-top: 40px;
        color: #777;
        font-size: 14px;
        text-align: center;
    }
    .logo {
        max-width: 180px;
        margin-bottom: 20px;
    }
</style>

<div class="header">
    <img src="https://venuefy.com/logo-white.png" alt="Venuefy Logo" class="logo">
    <div class="invoice-number">INVOICE #{{ $pembayaran->referensi_pembayaran }}</div>
    <div class="thank-you">Terima kasih telah melakukan pembayaran</div>
</div>

<div class="highlight-box">
    Pembayaran Anda telah kami terima. Berikut adalah ringkasan transaksi Anda:
</div>

<div class="section-title">📊 Detail Pembayaran</div>
<div class="detail-item">
    <div class="detail-label">Tanggal Pembayaran</div>
    <div class="detail-value">{{ $pembayaran->waktu_pembayaran->format('d F Y H:i') }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Metode Pembayaran</div>
    <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $pembayaran->metode_pembayaran)) }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Jumlah Pembayaran</div>
    <div class="detail-value" style="color: #6e48aa; font-weight: 600;">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Status</div>
    <div class="detail-value" style="color: #4CAF50; font-weight: 600;">{{ ucfirst($pembayaran->status) }}</div>
</div>

<div class="section-title">🏛 Detail Pemesanan</div>
<div class="detail-item">
    <div class="detail-label">Nama Gedung</div>
    <div class="detail-value">{{ $pembayaran->pemesanan->gedung->nama }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Tanggal Acara</div>
    <div class="detail-value">{{ $pembayaran->pemesanan->tanggal_mulai->format('d F Y') }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Waktu</div>
    <div class="detail-value">{{ $pembayaran->pemesanan->tanggal_mulai->format('H:i') }} - {{ $pembayaran->pemesanan->tanggal_selesai->format('H:i') }}</div>
</div>
<div class="detail-item">
    <div class="detail-label">Nama Acara</div>
    <div class="detail-value">{{ $pembayaran->pemesanan->nama_acara }}</div>
</div>

<div class="button-container" style="color: #333">
    @component('mail::button', ['url' => $url, 'color' => 'purple'])
        <span style="color: #6e48aa;">🔍 Lihat Detail Pemesanan</span>
    @endcomponent
</div>

<div class="footer">
    <p>Jika Anda memiliki pertanyaan, hubungi kami di <a href="mailto:support@venuefy.com" style="color: #6e48aa;">support@venuefy.com</a></p>
    <p>© {{ date('Y') }} Venuefy. All rights reserved.</p>
</div>
@endcomponent