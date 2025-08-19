<?php

// ? Esta tabla es para acceso user.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privacy', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 50)->nullable(false);
            $table->string('device', 100)->nullable(false);
            $table->integer('is_active')->nullable(false);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('privacy');
    }
};
