<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tx_doc', 20);
            $table->unsignedBigInteger('tx_type');
            $table->unsignedBigInteger('supplier');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('tx_in');
            $table->unsignedBigInteger('tx_out');
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
        Schema::dropIfExists('item_transactions');
    }
}
