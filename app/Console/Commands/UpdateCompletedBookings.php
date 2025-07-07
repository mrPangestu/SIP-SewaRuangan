<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use Carbon\Carbon;

class UpdateCompletedBookings extends Command
{
    protected $signature = 'bookings:update-completed';
    protected $description = 'Update booking status to completed after 1 hour past end time';

    public function handle()
    {
        $now = Carbon::now();
        
        Pemesanan::whereIn('status', ['dibayar', 'dikonfirmasi'])
            ->where('tanggal_selesai', '<=', $now->subHour())
            ->whereDoesntHave('pembayaran', function($query) {
                $query->where('status', '!=', 'completed');
            })
            ->chunkById(100, function ($pemesanans) {
                foreach ($pemesanans as $pemesanan) {
                    if (!cache()->has('processing_pemesanan_'.$pemesanan->id_pemesanan)) {
                        cache()->put('processing_pemesanan_'.$pemesanan->id_pemesanan, true, 10);
                        
                        $pemesanan->update(['status' => 'selesai']);
                        
                        cache()->forget('processing_pemesanan_'.$pemesanan->id_pemesanan);
                    }
                }
            });

        $this->info('Completed bookings updated successfully.');
    }
}