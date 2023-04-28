<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('name',256)->nullable();
            $table->string('password',100);
            $table->string('email',256)->nullable();;
            $table->string('phone_number',24);
            $table->string('user_name',256)->nullable();
            $table->string('user_groups_ids',100)->nullable();
            $table->smallInteger('sub_contractor')->comment('1. Sub Contractor')->description('1. Sub Contractor');
            $table->string('site_ids',100)->nullable();
            $table->string('role_ids',100)->nullable();
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
        Schema::dropIfExists('staff_details');
    }
}
