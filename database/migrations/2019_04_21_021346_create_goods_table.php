<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code');
            $table->string('desc')->nullable();
            $table->integer('margin');
            $table->integer('value');
            $table->integer('status');
            $table->integer('last_buy_pricelist')->nullable();
            $table->string('barcode_master')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('avg_price_status');
            $table->integer('avg_price')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('tax')->nullable();
            $table->integer('unit_id')->unsigned();
            $table->integer('cogs_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
