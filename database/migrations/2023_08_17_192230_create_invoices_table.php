<?php

// ? Esta tabla se debe crear solo para el cliente.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', static function (Blueprint $table) {
            $table->id();
            $table->string('email', 100)->nullable(false);
            $table->string('name_bank', 200)->nullable(false);
            $table->string('concept', 200)->nullable(false);
            $table->string('beneficiary', 200)->nullable(false);
            $table->string('account_number_bank', 200)->nullable(false);
            $table->string('account_key', 200)->nullable(false);
            $table->string('invoice_day', 2)->nullable(false);
            $table->integer('round_index')->nullable(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
