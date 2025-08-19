<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('account_number', 255);
            $table->string('account_name', 255);
            $table->integer('is_active')->nullable(false);
            $table->bigInteger('user_id')->nullable(false);
            $table->foreignId('water_meter_id')->references('id')->on('water_meters');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
