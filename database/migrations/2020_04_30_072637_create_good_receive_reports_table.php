<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodReceiveReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_receive_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gi_id');
            $table->string('gr_no');
            $table->string('gr_date');
            $table->string('user');
            $table->string('supplier');
            $table->string('item_code');
            $table->string('specification');
            $table->unsignedBigInteger('request_qty');
            $table->string('unit');
            $table->string('packing');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('sup_id');
            $table->unsignedBigInteger('item_id');
            $table->string('rep_code');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('itemtx_id')->nullable();
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
        Schema::dropIfExists('good_receive_reports');
    }
}
