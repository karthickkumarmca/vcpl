<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->integer('site_id')->default('0');
            $table->integer('notification_sender_id')->default('0');
            $table->integer('notification_receiver_id')->default('0');
            $table->integer('user_type')->default('0');
            $table->tinyInteger('notification_type')->default('0')->comments('1-Cement');
            $table->tinyInteger('read_status')->default('0')->comments('1-Read');
            $table->integer('updated_by')->default('0');
            $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('notifications');
    }
}
