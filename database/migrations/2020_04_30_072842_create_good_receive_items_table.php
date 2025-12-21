<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodReceiveItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_receive_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gr_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('item_qty');
            $table->decimal('item_cost', 8, 2);
            $table->unsignedBigInteger('sup_id');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('status');
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
        Schema::dropIfExists('good_receive_items');
    }
}
