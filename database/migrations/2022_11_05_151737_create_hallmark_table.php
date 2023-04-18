<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallmarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hallmark', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->double('rate')->nullable()->dafault('0');
            $table->smallInteger('status')->comment('1. Active 2. Deactive')->description('1. Active 2. Deactive');
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->bigInteger('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('hallmark');
    }
}
