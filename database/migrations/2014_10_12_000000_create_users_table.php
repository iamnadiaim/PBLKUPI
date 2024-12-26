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
        Schema::create('users', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('id_usaha');
            $table->string('nama');
            $table->string('email');
            $table->string('alamat')->nullable();
            $table->string('no_telepon');
            $table->string('password');
            $table->string('img_profile')->nullable(true);
            $table->timestamps();

            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
