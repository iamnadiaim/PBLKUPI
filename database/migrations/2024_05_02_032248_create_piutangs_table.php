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
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('id_kategori_hutang')->unsigned()->nullable();
            $table->unsignedBigInteger('id_usaha');
            $table->string('nama');
            $table->string('tanggal_pinjaman');
            $table->string('tanggal_jatuh_tempo');
            $table->string('jumlah_piutang');
            $table->string('jumlah_cicilan');
            $table->string('sisa_piutang');

            $table->boolean('status')->default(false);
            // $table->string('pembayaran')->nullable();
            // $table->string('status')->nullable();

            $table->timestamps();
            
            // Add foreign key constraints if needed (example)
            // $table->foreign('id_kategori_hutang')->references('id')->on('kategori_hutangs');
            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutangs');
    }
};