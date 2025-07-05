<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        $bookings = DB::table('pemesanan')->get();
        $paymentStatuses = ['menunggu', 'berhasil', 'gagal'];
        $paymentMethods = ['Bank Transfer', 'Virtual Account', 'E-Wallet', 'Credit Card'];

        $payments = [];
        $now = Carbon::now();

        foreach ($bookings as $booking) {
            // Determine payment status based on booking status
            $paymentStatus = 'menunggu';
            if ($booking->status === 'dibayar' || $booking->status === 'dikonfirmasi' || $booking->status === 'selesai') {
                $paymentStatus = 'berhasil';
            } elseif ($booking->status === 'dibatalkan') {
                $paymentStatus = 'gagal';
            }

            $paymentDate = Carbon::parse($booking->created_at)->addHours(rand(1, 24));

            $payments[] = [
                'id_pembayaran' => Str::uuid(),
                'pemesanan_id' => $booking->id_pemesanan,
                'jumlah' => $booking->total_harga,
                'status' => $paymentStatus,
                'bukti_transfer' => $paymentStatus === 'berhasil' ? 'bukti_' . Str::random(10) . '.jpg' : null,
                'tanggal' => $paymentDate,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('pembayaran')->insert($payments);
    }
}