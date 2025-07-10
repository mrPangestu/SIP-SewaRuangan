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
            $table->enum('jenis_pembayaran', ['deposit', 'pelunasan']);
            $table->decimal('jumlah', 13, 2);
            $table->enum('status', ['pending',  'completed', 'failed', 'expired']);
            $table->datetime('waktu_pembayaran')->nullable();
            $table->string('referensi_pembayaran');
            $table->string('snap_token')->nullable();
            $table->string('va_number')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->boolean('invoice_sent')->default(false);
            $table->timestamps();

            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan');
        });
    }

    public function down()
    {
        
    }
};