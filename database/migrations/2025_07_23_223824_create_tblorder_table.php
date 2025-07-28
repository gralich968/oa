<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblorder', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('companyCode');
            $table->integer('orderNumber');
            $table->date('orderDate');
            $table->integer('partenerRef');
            $table->date('dueDate');
            $table->integer('orderType');
            $table->integer('positionsposId');
            $table->integer('positioncompanyCode');
            $table->string('itemNumber');
            $table->integer('requestQty');
            $table->string('positionuom');
            $table->integer('sparenuber1');
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
        Schema::dropIfExists('tblorder');
    }
}
