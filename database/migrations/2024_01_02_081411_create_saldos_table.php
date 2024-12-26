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
        Schema::create('saldos', function (Blueprint $table) {
            $table->id();
            $table->string('saldo');
            $table->text('description')->nullable(true); 
            $table->unsignedBigInteger('id_usaha'); // Adding 'user_id' as a foreign key
            $table->timestamps();

            // Defining foreign key constraint
            $table->foreign('id_usaha')->references('id')->on('usaha')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldos');
    }
};
