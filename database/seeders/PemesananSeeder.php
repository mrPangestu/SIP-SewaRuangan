<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemesananSeeder extends Seeder
{
    public function run()
    {
        // Get all users and venues
        $users = DB::table('users')->pluck('id');
        $gedungs = DB::table('gedung')->get();

        if ($users->isEmpty() || $gedungs->isEmpty()) {
            $this->command->error('Please seed users and gedungs first!');
            return;
        }

        $statuses = [
            'menunggu_pembayaran', 'deposit', 'dibayar', 
            'dikonfirmasi', 'selesai', 'dibatalkan'
        ];
        
        $eventNames = [
            'Seminar Nasional', 'Pernikahan', 'Workshop', 'Pelatihan', 'Pameran',
            'Turnamen Olahraga', 'Konser', 'Rapat Tahunan', 'Lomba', 'Bazaar',
            'Konferensi', 'Syukuran', 'Reuni', 'Festival', 'Launching Produk',
            'Hackathon', 'Expo', 'Job Fair', 'Meetup', 'Webinar',
            'Peluncuran Buku', 'Pemutaran Film', 'Pertunjukan Teater', 'Pesta Ulang Tahun', 'Gathering',
            'Training Karyawan', 'Sosialisasi Produk', 'Audisi', 'Pencarian Bakat', 'Market Day'
        ];

        $bookings = [];
        $now = Carbon::now();

        // Create bookings with different time scenarios
        for ($i = 0; $i < 30; $i++) {
            $userIndex = $i % count($users);
            $gedung = $gedungs[$i % count($gedungs)];
            
            // Determine time scenarios based on index
            if ($i < 5) {
                // Very old pending (should be deleted)
                $startDate = $now->copy()->addDays(rand(5, 30));
                $createdAt = $now->copy()->subDays(2);
                $status = 'menunggu_pembayaran';
            } elseif ($i < 10) {
                // Recent pending (should remain)
                $startDate = $now->copy()->addDays(rand(5, 30));
                $createdAt = $now->copy()->subHours(12);
                $status = 'menunggu_pembayaran';
            } elseif ($i < 15) {
                // Deposit with event already started (should be deleted)
                $startDate = $now->copy()->subHours(2);
                $createdAt = $now->copy()->subDays(5);
                $status = 'deposit';
            } elseif ($i < 20) {
                // Deposit with event coming soon (for reminder)
                $startDate = $now->copy()->addDays(2);
                $createdAt = $now->copy()->subDays(5);
                $status = 'deposit';
            } elseif ($i < 25) {
                // Completed events (some should be deleted)
                $hoursAfterEvent = ($i % 2 == 0) ? 3 : 0.5; // Some older than 1 hour, some not
                $startDate = $now->copy()->subHours(5 + $hoursAfterEvent);
                $createdAt = $now->copy()->subDays(5);
                $status = ($i % 2 == 0) ? 'dibayar' : 'dikonfirmasi';
            } else {
                // Cancelled bookings (some should be deleted)
                $daysSinceCancelled = ($i % 2 == 0) ? 2 : 0.5; // Some older than 1 day, some not
                $startDate = $now->copy()->addDays(rand(5, 30));
                $createdAt = $now->copy()->subDays($daysSinceCancelled);
                $status = 'dibatalkan';
            }

            $endDate = $startDate->copy()->addHours(rand(4, 12));
            $durationHours = $endDate->diffInHours($startDate);
            $totalPrice = $gedung->harga * ceil($durationHours / 6); // Price per 6-hour block
            $depositAmount = ($status === 'deposit') ? $totalPrice * 0.2 : 0;
            $remainingAmount = $totalPrice - $depositAmount;

            // Set payment dates based on status
            $depositPaidAt = ($status === 'deposit' || $status === 'dibayar' || $status === 'dikonfirmasi') 
                ? $createdAt->copy()->addHours(1) 
                : null;
            
            $fullPaymentPaidAt = ($status === 'dibayar' || $status === 'dikonfirmasi') 
                ? $createdAt->copy()->addDays(1) 
                : null;

            $bookings[] = [
                'id_pemesanan' => Str::uuid(),
                'user_id' => $users[$userIndex],
                'id_gedung' => $gedung->id_gedung,
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'nama_acara' => $eventNames[$i],
                'total_harga' => $totalPrice,
                'deposit_amount' => $depositAmount,
                'remaining_amount' => $remainingAmount,
                'status' => $status,
                'deposit_paid_at' => $depositPaidAt,
                'full_payment_paid_at' => $fullPaymentPaidAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'version' => 0,
                'reminder_sent_at' => null,
            ];
        }

        DB::table('pemesanan')->insert($bookings);

        $this->command->info('Successfully seeded 30 bookings with various scenarios!');
        $this->command->info('Breakdown by status:');
        $counts = array_count_values(array_column($bookings, 'status'));
        foreach ($counts as $status => $count) {
            $this->command->info("- {$status}: {$count} bookings");
        }
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'menunggu_pembayaran':
                return 'warning';
            case 'deposit':
                return 'info';
            case 'dibayar':
                return 'primary';
            case 'dikonfirmasi':
                return 'success';
            case 'selesai':
                return 'secondary';
            case 'dibatalkan':
                return 'danger';
            default:
                return 'light';
        }
    }

    public function scopeStatusFilter($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }
}