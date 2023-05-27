<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabourMovementListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labour_movement_list', function (Blueprint $table) {
           $table->bigIncrements('id');
           $table->bigInteger('sub_contractor')->comment('Sub Contractor')->description('Sub Contractor');
           $table->string('labour_category')->nullable();
           $table->integer('number_of_labour')->nullable()->default('0');
           $table->tinyInteger('shift_id')->comment('1-Day 2-Night')->description('1-Day 2-Night');
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
        Schema::dropIfExists('labour_movement_list');
    }
}
