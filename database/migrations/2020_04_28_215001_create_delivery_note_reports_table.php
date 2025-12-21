<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNoteReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_note_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('si_id');
            $table->unsignedBigInteger('dn_id');
            $table->string('note_no');
            $table->string('user');
            $table->string('costcentre');
            $table->string('dn_date');
            $table->string('sign_date')->nullable();
            $table->string('item_code');
            $table->string('specification');
            $table->unsignedBigInteger('request_qty');
            $table->string('unit');
            $table->string('packing');
            $table->unsignedBigInteger('unit_price');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('so_id')->nullable();
            $table->string('so_no')->nullable();
            $table->unsignedBigInteger('cc_id');
            $table->unsignedBigInteger('item_id');
            $table->string('rep_code');
            $table->string('approver');
            $table->string('approve_date')->nullable();
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
        Schema::dropIfExists('delivery_note_reports');
    }
}
