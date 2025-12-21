<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('so_id');
            $table->string('so_no');
            $table->unsignedBigInteger('dep_id');
            $table->unsignedBigInteger('cc_id');
            $table->unsignedBigInteger('userext_id');
            $table->string('remarks')->nullable();
            $table->string('sign_image')->nullable();
            $table->dateTime('sign_date', 0)->nullable();
            $table->unsignedBigInteger('userint_id');
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
        Schema::dropIfExists('delivery_notes');
    }
}
