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
        Schema::create('itemShoppingList', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('itemId')->unsigned();
            // TODO cascade?
//            $table->foreign('item_id')
//                ->references('id')
//                ->on('items')
//                ->onDelete('cascade')
//                ->onUpdate('cascade');

            $table->integer('shoppingListId')->unsigned();

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
        Schema::dropIfExists('itemShoppingList');
    }
}
