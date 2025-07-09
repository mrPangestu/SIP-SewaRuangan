<?php

namespace App\Mail;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;
    public $url;

    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
        $this->url = route('pemesanan.show', $pembayaran->id_pemesanan);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Invoice Pembayaran #' . $this->pembayaran->referensi_pembayaran,
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.invoice',
            with: [
                'pembayaran' => $this->pembayaran,
                'url' => $this->url,
            ]
        );
    }

    public function attachments()
    {
        return [];
    }

    public function build()
    {
        return $this->markdown('emails.invoice');
    }
}
