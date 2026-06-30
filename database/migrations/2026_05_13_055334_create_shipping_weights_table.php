<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_weights', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->decimal('min_weight', 8,2)->nullable();

            $table->decimal('max_weight', 8,2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_weights');
    }
};