<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kalender_ketersediaan', function (Blueprint $table) {
            $table->uuid('id_kalender')->primary();
            $table->uuid('id_gedung');
            $table->datetime('tanggal');
            $table->enum('status', ['tersedia', 'tidak_tersedia', 'dipesan'])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            
            $table->unique(['id_gedung', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kalender_ketersediaan');
    }
};