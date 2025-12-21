<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('specification')->nullable();
            $table->string('unit');
            $table->string('pack')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->decimal('price', 8, 2);
            $table->unsignedBigInteger('stock');
            $table->unsignedBigInteger('min');
            $table->string('location');
            $table->string('image');
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
        Schema::dropIfExists('items');
    }
}
