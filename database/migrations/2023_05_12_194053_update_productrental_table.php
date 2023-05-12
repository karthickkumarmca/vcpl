<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductrentalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('product_rental', function (Blueprint $table) {
            $table->bigInteger('unit_id')->nullable()->unsigned();
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('product_rental', function (Blueprint $table) {
            $table->dropColumn("unit_id");
        });
    }
}
