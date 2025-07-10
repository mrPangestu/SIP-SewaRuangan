<!DOCTYPE html>
<html>
<head>
    <title>Pengingat Pelunasan Manual</title>
</head>
<body>
    <h2>Pengingat Pelunasan Manual</h2>
    
    <p>Halo {{ $pemesanan->user->name }},</p>
    
    <p>Admin kami mengirimkan pengingat untuk menyelesaikan pembayaran pelunasan Anda:</p>
    
    <ul>
        <li>Gedung: {{ $pemesanan->gedung->nama_gedung }}</li>
        <li>Tanggal Acara: {{ $pemesanan->tanggal_mulai->format('d M Y H:i') }}</li>
        <li>Jumlah Pelunasan: Rp {{ number_format($remainingAmount, 0, ',', '.') }}</li>
        <li>Batas Waktu Pembayaran: {{ $dueDate }}</li>
    </ul>
    
    <p>Silakan klik link berikut untuk melakukan pembayaran pelunasan:</p>
    <a href="{{ $paymentUrl }}">
        Bayar Pelunasan Sekarang
    </a>
    
    <p>Jika Anda sudah melakukan pembayaran, abaikan email ini.</p>
    
    <p>Terima kasih,</p>
    <p>Tim Admin</p>
</body>
</html>