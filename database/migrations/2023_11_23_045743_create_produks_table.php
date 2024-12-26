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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usaha');
            $table->string('kode_produk');
            $table->string('nama_produk');
            $table->String('ukuran');
            $table->String('harga');
            $table->integer('stok');
            $table->timestamps();

            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
