<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseQuantityRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase_quantity_requests')) {

            Schema::create('purchase_quantity_requests', function (Blueprint $table) {

                $table->id();

                $table->unsignedBigInteger('seller_id');
                $table->unsignedBigInteger('product_id');

                $table->integer('old_quantity');
                $table->integer('requested_quantity');

                $table->enum('status', [
                    'pending',
                    'approved',
                    'rejected'
                ])->default('pending');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_quantity_requests');
    }
}