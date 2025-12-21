<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('dn_id')->nullable();
            $table->string('dn_no')->nullable();
            $table->dateTime('dn_date', 0)->nullable();
            $table->unsignedBigInteger('dep_id')->nullable();
            $table->unsignedBigInteger('cc_id')->nullable();
            $table->unsignedBigInteger('extuser_id')->nullable();
            $table->unsignedBigInteger('appruser_id')->nullable();
            $table->dateTime('appr_date', 0)->nullable();
            $table->dateTime('request_date', 0)->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('status');
            $table->string('dn_no')->nullable();
            $table->dateTime('dn_date', 0)->nullable();
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
        Schema::dropIfExists('sales_orders');
    }
}
