<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('logo', 100);
            $table->string('add1', 100);
            $table->string('add2', 100);
            $table->string('add3', 100);
            $table->string('tel', 20);
            $table->string('fax', 20);
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
        Schema::dropIfExists('internal_companies');
    }
}
