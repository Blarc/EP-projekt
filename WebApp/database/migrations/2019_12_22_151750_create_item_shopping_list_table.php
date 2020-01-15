<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemShoppingListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_shopping_list', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('item_id')->unsigned();
            // TODO cascade?
//            $table->foreign('item_id')
//                ->references('id')
//                ->on('items')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');

            $table->integer('shopping_list_id')->unsigned();

            $table->integer('items_amount')
                ->unsigned()
                ->nullable();

//            $table->float('items_price')
//                ->unsigned()
//                ->nullable();

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
        Schema::dropIfExists('item_shopping_list');
    }
}
