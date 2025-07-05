<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PemesananSeeder extends Seeder
{
    public function run()
    {
        // Get all users and venues
        $users = DB::table('users')->pluck('id');
        $gedungs = DB::table('gedung')->get();

        $statuses = ['menunggu_pembayaran', 'dibayar', 'dikonfirmasi', 'selesai', 'dibatalkan'];
        $eventNames = [
            'Seminar Nasional', 'Pernikahan', 'Workshop', 'Pelatihan', 'Pameran',
            'Turnamen Olahraga', 'Konser', 'Rapat Tahunan', 'Lomba', 'Bazaar',
            'Konferensi', 'Syukuran', 'Reuni', 'Festival', 'Launching Produk'
        ];

        $bookings = [];
        $now = Carbon::now();

        for ($i = 0; $i < 15; $i++) {
            $userIndex = $i % count($users);
            $gedung = $gedungs[$i % count($gedungs)];
            
            $startDate = $now->copy()->addDays(rand(5, 30));
            $endDate = $startDate->copy()->addHours(rand(4, 12));

            $durationHours = $endDate->diffInHours($startDate);
            $totalPrice = $gedung->harga * ceil($durationHours / 6); // Price per 6-hour block

            $status = $statuses[min($i, 4)]; // Distribute statuses

            $bookings[] = [
                'id_pemesanan' => Str::uuid(),
                'user_id' => $users[$userIndex],
                'id_gedung' => $gedung->id_gedung,
                'tanggal_mulai' => $startDate,
                'tanggal_selesai' => $endDate,
                'nama_acara' => $eventNames[$i],
                'total_harga' => $totalPrice,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('pemesanan')->insert($bookings);
    }
}