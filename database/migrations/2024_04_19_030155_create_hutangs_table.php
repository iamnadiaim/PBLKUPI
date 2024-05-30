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
        Schema::create('hutangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usaha');
            $table->string('nama');
            $table->string('catatan')->nullable(); // Tambahkan kolom catatan
            $table->string('tanggal_pinjaman');
            $table->string('tanggal_jatuh_tempo');
            $table->string('jumlah_hutang');
            $table->string('jumlah_cicilan');
            $table->string('sisa_hutang');
            $table->boolean('status')->default(false);
            $table->timestamps();
            
            // Add foreign key constraints if needed (example)
            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutangs');
    }
};