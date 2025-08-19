<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_intervals', function (Blueprint $table) {
            $table->id();
            $table->decimal('min', 10, 2)->nullable(false);
            $table->decimal('max', 10, 2)->nullable(false);
            $table->decimal('cost', 10, 2)->nullable(false);
            $table->foreignId('consumption_h_id')->references('id')->on('consumption_h');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_intervals');
    }
};
