<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblproductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblproducts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->string('sku')->nullable();
            $table->string('upc')->nullable();
            $table->integer('slife')->nullable();
            $table->integer('trayod')->nullable();
            $table->integer('upt')->nullable();
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
        Schema::dropIfExists('tblproducts');
    }
}
