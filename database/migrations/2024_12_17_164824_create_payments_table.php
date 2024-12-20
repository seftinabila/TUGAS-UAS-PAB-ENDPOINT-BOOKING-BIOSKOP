<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('payment_type');  // Jenis pembayaran (e.g., 'credit_card', 'bank_transfer')
            $table->string('status');       // Status pembayaran (e.g., 'pending', 'paid', 'failed')
            $table->string('payment_url')->nullable(); // URL pembayaran untuk Midtrans
            $table->timestamps();

            // Menambahkan relasi dengan tabel bookings
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

