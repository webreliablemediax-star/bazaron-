<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddbazaronAttributesToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            // ⭐ bazaron Style Attributes (ALL NULLABLE - OLD DB SAFE)
            $table->string('model_number')->nullable()->after('compliance_certification');
            $table->string('model_name')->nullable();
            $table->string('manufacturer_name')->nullable();
            $table->string('generic_keyword')->nullable();
            $table->text('special_features')->nullable();

            $table->string('style')->nullable();
            $table->string('theme')->nullable();
            $table->string('material')->nullable();
            $table->string('compatible_devices')->nullable();

            $table->integer('unit_count')->nullable();
            $table->string('item_type_name')->nullable();
            $table->integer('number_of_items')->nullable();

            $table->string('water_resistance_level')->nullable();
            $table->string('target_gender')->nullable();
            $table->string('age_range_description')->nullable();
            $table->string('subject_character')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'model_number',
                'model_name',
                'manufacturer_name',
                'generic_keyword',
                'special_features',
                'style',
                'theme',
                'material',
                'compatible_devices',
                'unit_count',
                'item_type_name',
                'number_of_items',
                'water_resistance_level',
                'target_gender',
                'age_range_description',
                'subject_character',
            ]);
        });
    }
}