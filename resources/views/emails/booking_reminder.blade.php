@component('mail::message')
# Pengingat Pembayaran Pelunasan

Halo {{ $user->name }},

Anda memiliki pembayaran pelunasan yang harus diselesaikan untuk pemesanan berikut:

@component('mail::panel')
**Acara:** {{ $pemesanan->nama_acara }}  
**Lokasi:** {{ $gedung->nama_gedung }}  
**Tanggal:** {{ $pemesanan->tanggal_mulai->format('d M Y H:i') }}  
**Jumlah Pelunasan:** Rp {{ number_format($pemesanan->remaining_amount, 0, ',', '.') }}  
**Batas Waktu:** {{ $dueDate }}
@endcomponent

@component('mail::button', ['url' => $paymentUrl])
Bayar Pelunasan Sekarang
@endcomponent

Jika Anda sudah melakukan pembayaran, abaikan email ini.

Terima kasih,  
{{ config('app.name') }}
@endcomponent