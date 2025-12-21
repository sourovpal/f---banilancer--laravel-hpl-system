<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('englishname');
            $table->string('chinaname');
            $table->string('email');
            $table->string('englishaddress');
            $table->string('chinaaddress');
            $table->string('contact');
            $table->string('telephone1');
            $table->string('telephone2');
            $table->string('fax');
            $table->string('mobile');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
