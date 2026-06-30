<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_charges', function (Blueprint $table) {

            // ADMIN SHIPPING

            $table->decimal('admin_charge', 10, 2)
                  ->default(0)
                  ->after('total_charge_with_cod');

            $table->decimal('admin_shipping_gst', 10, 2)
                  ->default(0)
                  ->after('admin_charge');

            $table->decimal('admin_total_charge', 10, 2)
                  ->default(0)
                  ->after('admin_shipping_gst');



            // ADMIN COD

            $table->decimal('admin_cod_charge', 10, 2)
                  ->default(0)
                  ->after('admin_total_charge');

            $table->decimal('admin_cod_gst', 10, 2)
                  ->default(0)
                  ->after('admin_cod_charge');

            $table->decimal('admin_total_charge_with_cod', 10, 2)
                  ->default(0)
                  ->after('admin_cod_gst');

        });
    }

    public function down(): void
    {
        Schema::table('shipping_charges', function (Blueprint $table) {

            $table->dropColumn([

                'admin_charge',
                'admin_shipping_gst',
                'admin_total_charge',

                'admin_cod_charge',
                'admin_cod_gst',
                'admin_total_charge_with_cod'

            ]);

        });
    }
};