<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_h', static function (Blueprint $table) {
            $table->id();
            $table->dateTime('initial_date')->nullable(false);
            $table->dateTime('end_date')->nullable(false);
            $table->string('status_data', 1)->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_h');
    }
};
