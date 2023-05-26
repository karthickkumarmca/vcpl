<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableStockNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials_stock', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('uuid');

            $table->bigInteger('materials_category_id')->nullable()->unsigned();

            $table->bigInteger('site_id')->nullable()->unsigned();
            $table->foreign('site_id')->references('id')->on('site_info')->onDelete('SET NULL');

            $table->string('stock')->double(10,2)->default(0.00);
            
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
        Schema::dropIfExists('materials_stock');
    }
}
