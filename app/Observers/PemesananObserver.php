<?php

namespace App\Observers;

use App\Models\Pemesanan;
use Carbon\Carbon;

class PemesananObserver
{
    public function retrieved(Pemesanan $pemesanan)
    {
        // Cek jika status adalah 'dibayar' atau 'dikonfirmasi' dan sudah lewat 1 jam dari waktu selesai
        if (in_array($pemesanan->status, ['dibayar', 'dikonfirmasi']) && 
            Carbon::now()->gt($pemesanan->tanggal_selesai->addHour())) {
            
            // Pastikan tidak ada operasi lain yang sedang memproses pemesanan ini
            if (!cache()->has('processing_pemesanan_'.$pemesanan->id_pemesanan)) {
                cache()->put('processing_pemesanan_'.$pemesanan->id_pemesanan, true, 10);
                
                $pemesanan->update(['status' => 'selesai']);
                
                cache()->forget('processing_pemesanan_'.$pemesanan->id_pemesanan);
            }
        }
    }
}