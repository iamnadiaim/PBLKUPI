<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->json('properties')->nullable();
        
            // Kolom untuk menyimpan id dan tipe entitas (produk atau pendapatan)
            $table->unsignedBigInteger('entity_id')->nullable(); // ID dari produk atau pendapatan
            $table->string('entity_type')->nullable(); // Tipe entitas, bisa 'produk' atau 'pendapatan'
        
            $table->timestamps();
            $table->index('log_name');
        });        
        
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
}
