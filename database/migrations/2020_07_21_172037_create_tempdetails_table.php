<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tempdetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('mobile_number')->nullable();
            $table->string('otp');
            $table->integer('user_id');
            $table->longText('step_data1')->nullable();
            $table->longText('step_data2')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('expired_at');
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
        Schema::dropIfExists('tempdetails');
    }
}
