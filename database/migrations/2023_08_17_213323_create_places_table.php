<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', static function (Blueprint $table) {
            $table->id();
            $table->integer('is_active')->nullable();
            $table->foreignId('address_id')->references('id')->on('addresses');
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->foreignId('type_place_id')->references('id')->on('type_places');
            $table->foreignId('sector_id')->references('id')->on('sectors');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
