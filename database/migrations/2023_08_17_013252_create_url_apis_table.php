<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('url_apis', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->nullable(false);
            $table->string('database', 100)->nullable(false);
            $table->integer('quantity_r')->nullable(false); //
            $table->integer('quantity_w')->nullable(false); //
            $table->integer('is_active')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_apis');
    }
};
