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
            $table->boolean('invoice_sent')->default(false);
            $table->string('snap_token')->nullable();
            $table->string('va_number')->nullable();
            $table->string('payment_channel')->nullable();
            $table->string('store_code')->nullable();
            $table->text('payment_instructions')->nullable();
            $table->decimal('refund_amount', 13, 2)->nullable();
            $table->string('refund_note')->nullable();
            $table->dateTime('refund_date')->nullable();
            $table->string('refund_reference')->nullable();
                $table->timestamps();
        
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanan');
        });
    }

    public function down()
    {
        
    }
};