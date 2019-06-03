<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->increments('id');
            $table->date('periode');
            $table->string('warehouse_id');
            $table->integer('user_id')->unsigned();
            $table->enum('approve', ['0', '1', '2'])->default(0);// 0 : nothing, 1 : waiting, 2 : approve
            $table->integer('approve_id')->unsigned()->default(0);
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
        Schema::dropIfExists('stock_opnames');
    }
}
