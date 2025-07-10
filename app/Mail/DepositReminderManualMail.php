<?php

namespace App\Mail;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositReminderManualMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;

    public function __construct(Pemesanan $pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }

    public function build()
    {
        return $this->subject('Pengingat Pelunasan - ' . $this->pemesanan->gedung->nama_gedung)
            ->view('emails.deposit_reminder_manual')
            ->with([
                'pemesanan' => $this->pemesanan,
                'remainingAmount' => $this->pemesanan->remaining_amount,
                'dueDate' => $this->pemesanan->tanggal_mulai->subDays(1)->format('d M Y H:i'),
                'paymentUrl' => route('pembayaran.pelunasan', $this->pemesanan->id_pemesanan)
            ]);
    }
}