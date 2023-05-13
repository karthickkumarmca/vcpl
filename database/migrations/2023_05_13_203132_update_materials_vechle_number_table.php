<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMaterialsVechleNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('vehicle_materials', function (Blueprint $table) {
            $table->text("vehicle_no")->nullable();
            $table->date("insurance_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('vehicle_materials', function (Blueprint $table) {
            $table->dropColumn("vehicle_no");
            $table->dropColumn("insurance_date");
        });
    }
}
