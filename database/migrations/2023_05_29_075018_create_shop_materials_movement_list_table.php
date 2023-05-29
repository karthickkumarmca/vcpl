<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopMaterialsMovementListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_materials_movement_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->integer('material_id')->default('0');
            $table->integer('site_id')->default('0');
            $table->integer('supply_score')->default('0');
            $table->string('delivery_chellan_number')->default('0');
            $table->string('quantity')->default('0');
            $table->string('unit')->default('0');
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
        Schema::dropIfExists('shop_materials_movement_list');
    }
}
