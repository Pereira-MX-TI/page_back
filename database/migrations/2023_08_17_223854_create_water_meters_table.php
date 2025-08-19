<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_meters', function (Blueprint $table) {
            $table->id();
            $table->string('meter_serial', 150)->nullable(false);
            $table->string('radio', 150)->nullable(false);
            $table->bigInteger('module_id')->nullable(false);
            $table->bigInteger('product_id')->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->integer('is_macro')->unsigned()->nullable(false);
            $table->foreignId('gps_id')->references('id')->on('gps');
            $table->foreignId('place_id')->references('id')->on('places');
            $table->string('devEui', 150)->nullable(false);
            $table->string('device_id', 150)->nullable(false);
            $table->string('appKey', 150)->nullable(false);
            $table->string('frequency_id', 150)->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_meters');
    }
};
