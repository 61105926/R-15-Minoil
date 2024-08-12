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
        Schema::create('dataforms', function (Blueprint $table) {
            $table->id();
            $table->string('city');
            $table->string('chain');
            $table->string('room');
            $table->string('group');
            $table->string('line');
            $table->string('product');
            $table->string('expiration_date');
            $table->string('quantity');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataforms');
    }
};
