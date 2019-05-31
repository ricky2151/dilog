<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_company');
            $table->string('name_owner');
            $table->string('name_pic');
            $table->string('name_sales');
            $table->string('address');
            $table->string('no_telp_company');
            $table->string('no_telp_owner');
            $table->string('email')->unique();
            $table->string('fax');
            $table->string('npwp');
            $table->string('no_rek');
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
        Schema::dropIfExists('suppliers');
    }
}
