<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGstColumnsToShippingChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::table('shipping_charges', function (Blueprint $table) {

        $table->decimal('shipping_gst', 10, 2)->nullable();

        $table->decimal('total_charge', 10, 2)->nullable();

        $table->decimal('cod_gst', 10, 2)->nullable();

        $table->decimal('total_charge_with_cod', 10, 2)->nullable();

    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            //
        });
    }
}
