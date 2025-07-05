<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_gedung', function (Blueprint $table) {
            $table->uuid('id_kategori')->primary();
            $table->string('nama_kategori', 20);
            $table->text('deskripsi', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_gedung');
    }
};