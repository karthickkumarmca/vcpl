<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableToolsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tools_transactions', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('uuid');

            $table->bigInteger('vehicle_id')->nullable()->unsigned();
            $table->foreign('vehicle_id')->references('id')->on('vehicle_materials')->onDelete('SET NULL');

            $table->bigInteger('from_site_id')->nullable()->unsigned();
            $table->foreign('from_site_id')->references('id')->on('site_info')->onDelete('SET NULL');

            $table->bigInteger('site_id')->nullable()->unsigned();
            $table->foreign('site_id')->references('id')->on('site_info')->onDelete('SET NULL');

            $table->string('quantity')->double(10,2)->default(0.00);
            $table->string('opening_balance')->double(10,2)->default(0.00);
            $table->string('closing_balance')->double(10,2)->default(0.00);

            $table->smallInteger('type')->comment('1 TRANSFER 2 RECEIVED 3 ISSUED')->description('1 TRANSFER 2 RECEIVED 3 ISSUED');
            
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
        Schema::dropIfExists('tools_transactions');
    }
}
