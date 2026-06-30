<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationGalleriesTable extends Migration
{
    public function up()
    {
        // Agar pehle se exist ho to error na aaye
        if (!Schema::hasTable('variation_galleries')) {
            Schema::create('variation_galleries', function (Blueprint $table) {
                $table->id();

                // product reference (must match products.id = BIGINT UNSIGNED)
                $table->unsignedBigInteger('product_id');

                // variation combination reference (bazaron style)
                $table->unsignedBigInteger('variation_combination_id')->nullable();

                // gallery image path (media manager)
                $table->string('image');

                // sorting (hover thumbnails order)
                $table->integer('sort_order')->default(0);

                $table->timestamps();

                // Foreign Keys (SAFE)
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');

                $table->foreign('variation_combination_id')
                    ->references('id')
                    ->on('product_variation_combinations')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('variation_galleries');
    }
}