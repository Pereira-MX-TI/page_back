<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumption_h', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(false);
            $table->string('description', 255);
            $table->foreignId('initial_pickup_h_id')->references('id')->on('pickup_h');
            $table->foreignId('end_pickup_h_id')->references('id')->on('pickup_h');
            $table->dateTime('initial_date')->nullable(false);
            $table->dateTime('end_date')->nullable(false);
            $table->integer('check')->nullable(false);
            $table->string('type_calculation', 1)->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->foreignId('location_id')->references('id')->on('location');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumption_h');
    }
};
