<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSafetyComplianceFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('country_of_origin')->nullable();
        $table->string('manufacturer')->nullable();
        $table->string('importer_name')->nullable();
        $table->string('packer_details')->nullable();
        $table->text('safety_information')->nullable();
        $table->string('compliance_certification')->nullable();
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
            'country_of_origin',
            'manufacturer',
            'importer_name',
            'packer_details',
            'safety_information',
            'compliance_certification'
        ]);
    });
    }
}
