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
        Schema::create('pendapatans', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->unsignedBigInteger('id_usaha');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_pembeli');
            $table->string('jumlah_produk');
            $table->integer('harga_produk');
            $table->integer('total');
            $table->timestamps();
    
            // Define foreign key constraint
            $table->foreign('id_produk')->references('id')->on('produks')->onDelete('cascade');
            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendapatans');
    }
};
