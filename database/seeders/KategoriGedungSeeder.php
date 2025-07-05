<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriGedungSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'id_kategori' => Str::uuid(),
                'nama_kategori' => 'Gedung Serbaguna',
                'deskripsi' => 'Gedung fleksibel untuk berbagai jenis acara',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => Str::uuid(),
                'nama_kategori' => 'Aula Pertemuan',
                'deskripsi' => 'Ruangan formal untuk rapat dan seminar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => Str::uuid(),
                'nama_kategori' => 'Ballroom',
                'deskripsi' => 'Ruangan mewah untuk acara khusus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => Str::uuid(),
                'nama_kategori' => 'Gedung Olahraga',
                'deskripsi' => 'Fasilitas untuk kegiatan olahraga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_kategori' => Str::uuid(),
                'nama_kategori' => 'Ruang Pameran',
                'deskripsi' => 'Area untuk pameran dan ekshibisi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kategori_gedung')->insert($categories);
    }
}