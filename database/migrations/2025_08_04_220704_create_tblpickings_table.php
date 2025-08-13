<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblpickingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpickings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('position')->nullable();
            $table->text('product')->nullable();
            $table->string('quantity')->nullable();
            $table->string('picked')->nullable();
            $table->string('sku')->nullable();
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
        Schema::dropIfExists('tblpickings');
    }
}
