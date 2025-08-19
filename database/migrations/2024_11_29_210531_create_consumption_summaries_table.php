<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumption_summaries', function (Blueprint $table) {
            $table->id();
            $table->float('total_consumption');
            $table->float('start_index');
            $table->float('end_index');
            $table->string('date_calculation');
            $table->foreignId('water_meter_id')->constrained('water_meters');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumption_summaries');
    }
};
