<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_po')->nullable();
            $table->integer('supplier_id')->unsigned();
            $table->date('finish_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->integer('fine')->unsigned()->default(0);
            $table->enum('payment_type',['1','2']); // 1 untuk tempo, 2 untuk tunai
            $table->enum('type',['1','2','3']); // 1 untuk PO langsung, 2 untuk PO PR, 3 untuk PO min
            $table->integer('payment_terms')->default(0); 
            $table->integer('periode_id')->unsigned(); 
            $table->integer('approved_by_user_id')->unsigned()->nullable();
            $table->enum('status',['1','2','3','4']); //1 untuk New, 2 untuk Submitted, 3 untuk Approved, 4 untuk Finish
            $table->double('total')->default(0);
            $table->integer('is_completed')->default(0);
            $table->integer('purchase_request_id')->unsigned()->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
}
