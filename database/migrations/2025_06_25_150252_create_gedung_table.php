<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gedung', function (Blueprint $table) {
            $table->uuid('id_gedung')->primary();
            $table->uuid('id_kategori');
            $table->string('nama', 30);
            $table->text('lokasi');
            $table->enum('daerah', [
                'kota bandung utara', 'kota bandung barat',
                'kota bandung selatan', 'kota bandung timur',
                'kabupaten bandung barat', 'kabupaten bandung',
                'kota cimahi', 'kabupaten sumedang'
            ]);
            $table->integer('kapasitas');
            $table->text('fasilitas');
            $table->double('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable(); // <= tambahkan kolom image (nullable biar opsional)
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_gedung');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gedung');
    }
};