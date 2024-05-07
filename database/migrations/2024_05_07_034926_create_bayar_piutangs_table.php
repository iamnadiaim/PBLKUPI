<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bayar_piutangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usaha');
            $table->string('tanggal_pembayaran');
            $table->string('nama');
            $table->string('pembayaran');
            $table->string('jumlah');
            $table->timestamps();

            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayar_piutangs');
    }
};
