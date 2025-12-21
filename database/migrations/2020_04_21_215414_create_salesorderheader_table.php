<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesorderheaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salesorderheader', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('dn_id')->nullable();
            $table->unsignedBigInteger('dep_id');
            $table->unsignedBigInteger('cc_id')->nullable();
            $table->unsignedBigInteger('extuser_id')->nullable();
            $table->unsignedBigInteger('appruser_id')->nullable();
            $table->dateTime('appr_date', 0)->nullable();
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
        Schema::dropIfExists('salesorderheader');
    }
}
