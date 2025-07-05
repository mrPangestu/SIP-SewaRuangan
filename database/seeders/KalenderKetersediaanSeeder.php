<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KalenderKetersediaanSeeder extends Seeder
{
    public function run()
    {
        $gedungs = DB::table('gedung')->pluck('id_gedung');
        $statuses = ['tersedia', 'tidak_tersedia', 'dipesan'];
        
        $kalenders = [];
        for ($i = 1; $i <= 10; $i++) {
            $date = Carbon::now()->addDays(rand(1, 30))->setHour(rand(8, 18))->setMinute(0);
            $kalenders[] = [
                'id_kalender' => Str::uuid(),
                'id_gedung' => $gedungs->random(),
                'tanggal' => $date,
                'status' => $statuses[array_rand($statuses)],
                'keterangan' => rand(0, 1) ? 'Keterangan untuk jadwal ini' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('kalender_ketersediaan')->insert($kalenders);
    }
}