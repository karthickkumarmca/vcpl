<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchitectSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('architect_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');

            $table->bigInteger('site_id')->nullable()->unsigned();
            $table->foreign('site_id')->references('id')->on('site_info')->onDelete('SET NULL');

            $table->string('architect_name',256)->nullable();
            $table->string('cader',256)->nullable();
            $table->string('mobile_number',256)->nullable();
            $table->string('email_id',256)->nullable();
            $table->string('address',512)->nullable();

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
        Schema::dropIfExists('architect_sites');
    }
}
