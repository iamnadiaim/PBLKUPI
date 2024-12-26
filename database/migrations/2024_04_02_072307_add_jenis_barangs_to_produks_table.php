<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jenis_barang')->nullable();
            $table->foreign('id_jenis_barang')->references('id')->on('jenis_barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_barangs', function (Blueprint $table) {
            // $table->dropForeign(['id_jenis_barang']);
            // $table->dropColumn('id_jenis_barang');
        });
    }
};
