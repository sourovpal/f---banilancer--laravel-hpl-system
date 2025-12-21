<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qi_id');
            $table->string('qn_no');
            $table->string('qn_date');
            $table->string('user');
            $table->string('item_name');
            $table->string('costcenter');
            $table->string('specification');
            $table->unsignedBigInteger('request_qty');
            $table->string('unit');
            $table->string('pack');
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('dep_id');
            $table->string('rep_code');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cc_id');
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
        Schema::dropIfExists('quotation_reports');
    }
}
