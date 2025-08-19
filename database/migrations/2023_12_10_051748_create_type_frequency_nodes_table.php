<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_frequency_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_frequency');
            $table->string('band_id');
            $table->integer('base_frequency');
            $table->string('loraWan_version');
            $table->string('regional_params_version');
            $table->string('loraWan_class')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_frequency_nodes');
    }
};
