<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddcolumnOwershiptostaffidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ownership', function (Blueprint $table) {
            $table->bigInteger('staff_id')->nullable()->unsigned();
            $table->foreign('staff_id')->references('id')->on('staff_details')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ownership', function (Blueprint $table) {
            $table->dropColumn("staff_id");
        });
    }
}
