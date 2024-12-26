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
        Schema::create('jenis_barangs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_usaha')->nullable(true);
            $table->string('nama');
            $table->timestamps();
            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_barangs');
    }
};
