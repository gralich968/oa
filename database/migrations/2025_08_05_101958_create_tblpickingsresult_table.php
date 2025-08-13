<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblpickingsresultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpickingsresult', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position')->nullable();
            $table->string('product')->nullable();
            $table->string('quantity')->nullable();
            $table->string('picked')->nullable();
            $table->string('remain')->nullable();
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
        Schema::dropIfExists('tblpickingsresult');
    }
}
