<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->foreignId('gps_id')->references('id')->on('gps');
            $table->foreignId('type_location_id')->references('id')->on('type_locations');
            $table->foreignId('address_id')->references('id')->on('addresses');
            $table->foreignId('invoice_id')->references('id')->on('invoices');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
