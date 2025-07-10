<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->uuid('id_pemesanan')->primary();
            $table->uuid('user_id'); // Changed from id_penyewa to user_id
            $table->uuid('id_gedung');
            $table->datetime('tanggal_mulai');
            $table->datetime('tanggal_selesai'); // Fixed typo (note the double 's')
            $table->string('nama_acara', 30);
            $table->double('total_harga', 12, 2);
            $table->decimal('deposit_amount', 13, 2)->default(0);
            $table->decimal('remaining_amount', 13, 2)->default(0);
            $table->dateTime('deposit_paid_at')->nullable();
            $table->dateTime('full_payment_paid_at')->nullable();
            $table->enum('status', ['menunggu_pembayaran', 'deposit', 'dibayar', 'dikonfirmasi', 'selesai', 'dibatalkan'])
                    ->default('menunggu_pembayaran');
            $table->unsignedInteger('version')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('id_gedung')->references('id_gedung')->on('gedung');
                });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
    
};