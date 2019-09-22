<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_request_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_request_id')->unsigned();
            $table->integer('goods_id')->unsigned();
            $table->integer('qty')->unsigned();
            $table->float('total')->unsigned();
            $table->enum('status', [1, 0, -1])->default(0); // 1 : diterima, 0 : menunggu, -1 : ditolak
            $table->string('notes');
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
        Schema::dropIfExists('material_request_details');
    }
}
