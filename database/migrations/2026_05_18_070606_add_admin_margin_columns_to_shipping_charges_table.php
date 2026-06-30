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
        Schema::table('shipping_charges', function (Blueprint $table) {

            // Admin Margin On Shipping
            $table->decimal('admin_margin_shipping', 10, 2)
                  ->default(0.00)
                  ->after('admin_total_charge');

            // Admin Margin On COD
            $table->decimal('admin_margin_cod', 10, 2)
                  ->default(0.00)
                  ->after('admin_total_charge_with_cod');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_charges', function (Blueprint $table) {

            $table->dropColumn([
                'admin_margin_shipping',
                'admin_margin_cod'
            ]);

        });
    }
};