<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumption_d', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost_m3', 10, 2)->nullable(false);
            $table->decimal('cost', 10, 2)->nullable(false);
            $table->string('index_m', 255)->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->integer('status')->nullable(false);
            $table->foreignId('water_meter_id')->references('id')->on('water_meters');
            $table->foreignId('place_id')->references('id')->on('places');
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->foreignId('consumption_h_id')->references('id')->on('consumption_h');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumption_d');
    }
};
