<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_request_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_request_id')->unsigned();
            $table->integer('goods_id')->unsigned();
            $table->integer('qty')->unsigned();
            $table->string('notes');
            $table->integer('pricelist_id')->unsigned();
            $table->integer('supplier_id')->unsigned();
            $table->integer('price')->unsigned();
            $table->boolean('is_created_as_po');
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
        Schema::dropIfExists('purchase_request_details');
    }
}
