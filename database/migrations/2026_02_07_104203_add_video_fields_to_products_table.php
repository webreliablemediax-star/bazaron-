<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
    $table->string('product_video')->nullable()->after('description');
    $table->unsignedBigInteger('video_thumbnail_id')->nullable()->after('product_video');
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
    $table->dropColumn(['product_video', 'video_thumbnail_id']);
});

    }
}
