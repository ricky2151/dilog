<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpbmDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spbm_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('spbm_id')->unsigned();
            $table->integer('goods_id')->unsigned();
            $table->integer('qty')->unsigned();
            $table->string('notes')->nullable();
            $table->integer('defective_qty')->unsigned()->default(0);
            $table->integer('rack_id')->unsigned();
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
        Schema::dropIfExists('spbm_details');
    }
}
