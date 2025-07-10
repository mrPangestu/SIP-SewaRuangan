<?php

namespace App\Mail;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;

    public function __construct(Pemesanan $pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }

    public function build()
    {
        return $this->markdown('emails.booking_reminder')
            ->subject('Pengingat Pembayaran Pelunasan: ' . $this->pemesanan->nama_acara)
            ->with([
                'pemesanan' => $this->pemesanan,
                'user' => $this->pemesanan->user,
                'gedung' => $this->pemesanan->gedung,
                'dueDate' => $this->pemesanan->tanggal_mulai->subDays(1)->format('d M Y H:i'),
                'paymentUrl' => route('pembayaran.pelunasan', $this->pemesanan->id_pemesanan)
            ]);
    }
}
