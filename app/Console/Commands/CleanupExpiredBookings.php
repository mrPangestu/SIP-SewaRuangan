<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\BookingReminderMail;
use Illuminate\Support\Facades\Mail;

class CleanupExpiredBookings extends Command
{
    protected $signature = 'bookings:cleanup';
    protected $description = 'Clean up expired bookings based on their status';

    public function handle()
    {
        $this->cleanupPendingBookings();
        $this->cleanupDepositBookings();
        $this->cleanupCompletedBookings();
        $this->cleanupCancelledBookings();
        $this->sendReminderEmails();
        
        $this->info('Booking cleanup completed successfully.');
    }

    protected function cleanupPendingBookings()
    {
        try {
            $bookings = Pemesanan::where('status', 'menunggu_pembayaran')
                ->where('created_at', '<=', now()->subDay())
                ->get();

            $count = 0;
            
            foreach ($bookings as $booking) {
                try {
                    if ($booking->updateWithLock(['status' => 'dibatalkan'])) {
                        $booking->delete();
                        $count++;
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to update booking {$booking->id_pemesanan}: " . $e->getMessage());
                    continue;
                }
            }
            
            if ($count > 0) {
                Log::info("Deleted {$count} pending bookings older than 1 day");
            }
        } catch (\Exception $e) {
            Log::error("Error cleaning up pending bookings: " . $e->getMessage());
        }
    }

    protected function cleanupDepositBookings()
    {
        try {
            $count = Pemesanan::where('status', 'deposit')
                ->where('tanggal_mulai', '<=', now())
                ->delete();
                
            if ($count > 0) {
                Log::info("Deleted {$count} deposit bookings where event has started");
            }
        } catch (\Exception $e) {
            Log::error("Error cleaning up deposit bookings: " . $e->getMessage());
        }
    }

    protected function cleanupCompletedBookings()
    {
        try {
            $count = Pemesanan::whereIn('status', ['dibayar', 'dikonfirmasi'])
                ->where('tanggal_selesai', '<=', now()->subHour())
                ->delete();
                
            if ($count > 0) {
                Log::info("Deleted {$count} completed bookings older than 1 hour after event");
            }
        } catch (\Exception $e) {
            Log::error("Error cleaning up completed bookings: " . $e->getMessage());
        }
    }

    protected function cleanupCancelledBookings()
    {
        try {
            $count = Pemesanan::where('status', 'dibatalkan')
                ->where('updated_at', '<=', now()->subDay())
                ->delete();
                
            if ($count > 0) {
                Log::info("Deleted {$count} cancelled bookings older than 1 day");
            }
        } catch (\Exception $e) {
            Log::error("Error cleaning up cancelled bookings: " . $e->getMessage());
        }
    }

    protected function sendReminderEmails()
{
    try {
        $bookings = Pemesanan::where('status', 'deposit')
            ->whereBetween('tanggal_mulai', [
                now()->addDays(2),
                now()->addDays(2)->addHour() // 2 days from now ±1 hour window
            ])
            ->whereNull('reminder_sent_at')
            ->with('user', 'gedung')
            ->get();

        foreach ($bookings as $booking) {
            // Double check the booking is still valid
            if ($booking->status !== 'deposit') {
                Log::warning("Booking {$booking->id_pemesanan} is no longer in deposit status");
                continue;
            }

            try {
                Mail::to($booking->user->email)
                    ->send(new BookingReminderMail($booking));
                
                $booking->update(['reminder_sent_at' => now()]);
                Log::info("Sent reminder email for booking ID: {$booking->id_pemesanan}");
            } catch (\Exception $e) {
                Log::error("Failed to send reminder for booking {$booking->id_pemesanan}: " . $e->getMessage());
            }
        }
    } catch (\Exception $e) {
        Log::error("Error in reminder email process: " . $e->getMessage());
    }
}
    
}