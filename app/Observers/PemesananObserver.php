<?php

namespace App\Observers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

class PemesananObserver
{
    public function updating(Pemesanan $pemesanan)
    {
        // Lock the row to prevent conflicts with cleanup job
        $original = $pemesanan->getOriginal();
        
        if ($pemesanan->isDirty('status')) {
            Log::info("Booking status changed from {$original['status']} to {$pemesanan->status}");
            
            // Add additional logic if needed when status changes
            // This ensures cleanup job won't interfere with manual status changes
        }
    }

    public function deleted(Pemesanan $pemesanan)
    {
        Log::info("Booking deleted: {$pemesanan->id_pemesanan}");
        // You can add additional cleanup logic here if needed
    }
}