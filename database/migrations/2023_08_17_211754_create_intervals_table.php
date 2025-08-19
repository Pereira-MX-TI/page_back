<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intervals', function (Blueprint $table) {
            $table->id();
            $table->decimal('min', 10, 2)->nullable(false);
            $table->decimal('max', 10, 2)->nullable(false);
            $table->decimal('cost', 10, 2)->nullable(false);
            $table->foreignId('location_id')->references('id')->on('locations');
            $table->integer('is_active')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intervals');
    }
};
