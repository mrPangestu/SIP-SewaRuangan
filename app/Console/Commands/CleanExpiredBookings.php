<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanExpiredBookings extends Command
{
    protected $signature = 'bookings:clean';
    protected $description = 'Hapus pemesanan yang sudah melewati waktu dan belum dibayar';

    public function handle()
    {
        $threshold = Carbon::now()->subHours(2); // Hapus pemesanan yang belum dibayar dalam 2 jam
        $expiredBookings = Pemesanan::where('status', 'menunggu_pembayaran')
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Deleted {$expiredBookings} expired bookings.");
        
        return 0;
    }
}