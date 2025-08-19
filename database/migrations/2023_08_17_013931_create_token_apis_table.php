<?php

// ? Esta tabla es para acceso user.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('token_apis', function (Blueprint $table) {
            $table->id();
            $table->string('data', 1000)->nullable(false);
            $table->timestamp('expiration')->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->foreignId('url_api_id')->references('id')->on('url_apis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('token_apis');
    }
};
