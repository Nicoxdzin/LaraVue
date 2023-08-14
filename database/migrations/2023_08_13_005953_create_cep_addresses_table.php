<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cep_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('cep', 8)->unique();
            $table->string('state_initials', 2);
            $table->string('city');
            $table->string('neighborhood');
            $table->string('street');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cep_addresses');
    }
};
