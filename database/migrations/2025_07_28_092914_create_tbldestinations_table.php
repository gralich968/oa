<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbldestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbldestinations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('depo_name')->nullable();
            $table->integer('depo_code')->nullable();
            $table->string('depo_type')->nullable();
            $table->integer('depo_gln')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbldestinations');
    }
}
