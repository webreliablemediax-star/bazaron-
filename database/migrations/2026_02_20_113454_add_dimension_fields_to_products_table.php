<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 🟢 ITEM DIMENSIONS
        $table->string('item_length')->nullable();
        $table->string('item_length_unit')->nullable();
        $table->string('item_width')->nullable();
        $table->string('item_width_unit')->nullable();
        $table->string('item_height')->nullable();
        $table->string('item_height_unit')->nullable();

        // 🟢 PACKAGE DIMENSIONS
        $table->string('package_length')->nullable();
        $table->string('package_length_unit')->nullable();
        $table->string('package_width')->nullable();
        $table->string('package_width_unit')->nullable();
        $table->string('package_height')->nullable();
        $table->string('package_height_unit')->nullable();

        // 🟢 WEIGHT
        $table->string('package_weight')->nullable();
        $table->string('package_weight_unit')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
            'item_length',
            'item_length_unit',
            'item_width',
            'item_width_unit',
            'item_height',
            'item_height_unit',
            'package_length',
            'package_length_unit',
            'package_width',
            'package_width_unit',
            'package_height',
            'package_height_unit',
            'package_weight',
            'package_weight_unit',
        ]);
        });
    }
}
