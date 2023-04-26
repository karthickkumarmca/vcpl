<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCenteringMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');

            $table->bigInteger('material_id')->nullable();

            $table->bigInteger('property_material_id')->nullable()->unsigned();
            $table->foreign('property_material_id')->references('id')->on('categories')->onDelete('SET NULL');

            $table->bigInteger('units_id')->nullable()->unsigned();
            $table->foreign('units_id')->references('id')->on('units')->onDelete('SET NULL');

            $table->string('rate_unit')->double(10,2)->default(0.00);

            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();

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
        Schema::dropIfExists('centering_materials');
    }
}
