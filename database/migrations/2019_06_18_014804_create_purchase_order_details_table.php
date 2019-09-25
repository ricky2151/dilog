<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id')->unsigned();
            $table->integer('pricelist_id')->unsigned();
            $table->integer('goods_id')->unsigned();
            $table->integer('qty')->unsigned();
            $table->integer('subtotal')->unsigned();
            $table->integer('tax')->unsigned();
            $table->double('discount_percent')->unsigned()->nullabe();
            $table->double('discount_rupiah')->unsigned()->nullabe();
            $table->boolean('is_edited')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_details');
    }
}
