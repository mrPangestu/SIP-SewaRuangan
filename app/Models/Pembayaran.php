<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'id_pembayaran', 'id_pemesanan', 'metode_pembayaran', 'jenis_pembayaran',
        'jumlah', 'status', 'waktu_pembayaran', 'referensi_pembayaran', 
        'snap_token', 'va_number', 'payment_channel', 'bukti_pembayaran', 'invoice_sent',
    ];

    protected $casts = [
        'waktu_pembayaran' => 'datetime',
        'jumlah' => 'decimal:2'
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}