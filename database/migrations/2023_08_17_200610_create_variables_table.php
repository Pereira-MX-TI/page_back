<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variables', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('cost', 10, 2)->nullable(false);
            $table->bigInteger('location_id')->unsigned()->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variables');
    }
};
