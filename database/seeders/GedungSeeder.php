<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GedungSeeder extends Seeder
{
    private $bandungDistricts = [
        'kota bandung utara' => ['Cicendo', 'Cidadap', 'Sukasari', 'Coblong', 'Bandung Wetan'],
        'kota bandung barat' => ['Bandung Kulon', 'Cimahi', 'Cibeunying Kaler', 'Cibeunying Kidul'],
        'kota bandung selatan' => ['Bandung Kidul', 'Buahbatu', 'Margaasih', 'Rancasari'],
        'kota bandung timur' => ['Arcamanik', 'Antapani', 'Ujungberung', 'Gedebage'],
        'kabupaten bandung barat' => ['Lembang', 'Cisarua', 'Parongpong', 'Cihampelas'],
        'kabupaten bandung' => ['Soreang', 'Dayeuhkolot', 'Baleendah', 'Ciparay'],
        'kota cimahi' => ['Cimahi Selatan', 'Cimahi Tengah', 'Cimahi Utara'],
        'kabupaten sumedang' => ['Sumedang Utara', 'Sumedang Selatan', 'Tanjungkerta']
    ];

    public function run()
    {
        $categoryIds = DB::table('kategori_gedung')->pluck('id_kategori');
        $venues = [];
        $daerahKeys = array_keys($this->bandungDistricts);

        for ($i = 0; $i < 20; $i++) {
            $categoryIndex = $i % 5;
            $daerahIndex = $i % count($daerahKeys);
            $daerah = $daerahKeys[$daerahIndex];
            $district = $this->bandungDistricts[$daerah][array_rand($this->bandungDistricts[$daerah])];
            
            $venues[] = [
                'id_gedung' => Str::uuid(),
                'id_kategori' => $categoryIds[$categoryIndex],
                'nama' => $this->generateVenueName($categoryIndex),
                'lokasi' => "Jl. " . $this->generateStreetName() . " No. " . ($i + 1) . ", Kec. " . $district,
                'daerah' => $daerah,
                'kapasitas' => rand(50, 1000),
                'fasilitas' => $this->generateFacilities($categoryIndex),
                'harga' => $this->generatePrice($categoryIndex),
                'deskripsi' => $this->generateDescription($categoryIndex, $district, $daerah),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('gedung')->insert($venues);
    }

    private function generateVenueName($categoryIndex)
    {
        $prefixes = [
            'Grand', 'Mega', 'Royal', 'Platinum', 'Elite',
            'Prestige', 'Prime', 'Luxury', 'Exclusive', 'Premium'
        ];

        $names = [
            ['Serbaguna Indah', 'Multifungsi', 'Graha Utama', 'Wisma Serba Guna'],
            ['Aula Utama', 'Convention Hall', 'Meeting Center', 'Ruang Rapat'],
            ['Grand Ballroom', 'Royal Hall', 'Platinum Room', 'The Grand Ball'],
            ['Sport Center', 'Arena Olahraga', 'GOR Modern', 'Fitness Hall'],
            ['Exhibition Hall', 'Gallery Space', 'Pameran Utama', 'Showroom']
        ];

        return $prefixes[array_rand($prefixes)] . ' ' . $names[$categoryIndex][array_rand($names[$categoryIndex])];
    }

    private function generateStreetName()
    {
        $streets = [
            'Gegerkalong', 'Dago', 'Setiabudi', 'Pasteur', 'Sukajadi',
            'Cihampelas', 'Riau', 'Asia Afrika', 'Merdeka', 'Sudirman',
            'Ahmad Yani', 'Buahbatu', 'Soekarno Hatta', 'Pahlawan', 'Cikutra'
        ];

        return $streets[array_rand($streets)];
    }

    private function generateFacilities($categoryIndex)
    {
        $baseFacilities = "AC, Toilet, Parkir, Listrik";
        
        $specificFacilities = [
            "Panggung, Sound System, Kursi, Meja, Catering",
            "Proyektor, Whiteboard, Sound System, Kursi Rapat",
            "Panggung Mewah, Lighting System, Dekorasi, Catering Premium",
            "Lapangan, Perlengkapan Olahraga, Ruang Ganti, Tribun",
            "Booth, Lighting Khusus, Area Display, Ruang Presentasi"
        ];

        return $baseFacilities . ", " . $specificFacilities[$categoryIndex];
    }

    private function generatePrice($categoryIndex)
    {
        $basePrices = [5000000, 3000000, 10000000, 2000000, 4000000];
        $multiplier = rand(8, 20) / 10; // Random between 0.8 to 2.0
        return $basePrices[$categoryIndex] * $multiplier;
    }

    private function generateDescription($categoryIndex, $district, $daerah)
    {
        $types = [
            "gedung serbaguna yang cocok untuk berbagai acara",
            "aula pertemuan yang nyaman untuk seminar dan rapat",
            "ballroom mewah untuk acara spesial dan pernikahan",
            "gedung olahraga dengan fasilitas lengkap",
            "ruang pameran yang luas untuk berbagai ekshibisi"
        ];

        return "Terletak di Kec. " . $district . ", " . ucfirst($daerah) . ". " . 
               $types[$categoryIndex] . ". Fasilitas lengkap dan pelayanan profesional untuk memenuhi kebutuhan acara Anda.";
    }
}