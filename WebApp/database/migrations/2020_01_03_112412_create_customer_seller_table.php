<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_seller', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('seller_id')->unsigned();
            // TODO cascade?
//            $table->foreign('item_id')
//                ->references('id')
//                ->on('items')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');

            $table->integer('customer_id')->unsigned();

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
        Schema::dropIfExists('customer_seller');
    }
}
