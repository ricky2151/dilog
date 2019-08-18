<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_mr')->unique()->nullable();
            $table->integer('division_id')->unsigned();
            $table->integer('request_by_user_id')->unsigned();
            $table->integer('approved_by_user_id')->unsigned()->nullable();
            $table->boolean('status')->default(0); // 0 : belum direspon, 1 : sudah direspon
            $table->integer('periode_id')->unsigned();
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
        Schema::dropIfExists('material_requests');
    }
}
