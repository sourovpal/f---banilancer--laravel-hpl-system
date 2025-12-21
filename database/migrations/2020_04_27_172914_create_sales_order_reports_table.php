<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('si_id');
            $table->string('order_no');
            $table->string('user');
            $table->string('costcentre');
            $table->string('order_date');
            $table->string('item_code');
            $table->string('specification');
            $table->unsignedBigInteger('request_qty');
            $table->string('unit');
            $table->string('packing');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total_price');
            $table->string('approver');
            $table->string('approve_date')->nullable();
            $table->unsignedBigInteger('dn_id')->nullable();
            $table->string('dn_no')->nullable();
            $table->string('dn_date')->nullable();
            $table->unsignedBigInteger('cc_id');
            $table->unsignedBigInteger('item_id');
            $table->string('rep_code');
            $table->unsignedBigInteger('dep_id');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('sales_order_reports');
    }
}
