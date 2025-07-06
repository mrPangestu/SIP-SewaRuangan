<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id_pembayaran')->primary();
            $table->uuid('id_pemesanan');
            $table->string('metode_pembayaran');
            $table->decimal('jumlah', 13, 2);
            $table->enum('status', ['pending', 'completed', 'failed', 'expired']);
            $table->datetime('waktu_pembayaran')->nullable();
            $table->string('referensi_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};