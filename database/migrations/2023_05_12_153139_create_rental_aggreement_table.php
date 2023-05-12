<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalAggreementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_agreement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');

            $table->bigInteger('property_id')->nullable()->unsigned();
            $table->foreign('property_id')->references('id')->on('property_name')->onDelete('SET NULL');

            $table->string('tenant_name',256)->nullable();
            $table->date('rent_start_date')->nullable();
            $table->date('rent_end_date')->nullable();

            $table->bigInteger('rental_area')->nullable();
            $table->string('rental_amount')->double(10,2)->default(0.00);
            $table->string('maintainance_charge')->double(10,2)->default(0.00);
            $table->string('next_increment')->double(10,2)->default(0.00);

            $table->string('aadhar_number',128)->nullable();
            $table->string('pan_number',128)->nullable();
            $table->string('gst_in',128)->nullable();
            $table->string('contact_person_name',128)->nullable();
            $table->string('contact_person_mobile_number',128)->nullable();

            $table->string('alternative_contact_person_name',128)->nullable();
            $table->string('alternative_contact_person_mobile_number',128)->nullable();

            $table->bigInteger('present_rental_rate')->nullable();
            $table->string('advance_paid')->double(10,2)->default(0.00);

            $table->smallInteger('payment_mode')->comment('1. Wire Transfer 2. Cash  3. Online')->description('1. Wire Transfer 2. Cash  3. Online');

            $table->string('aadhar_proof',128)->nullable();
            $table->string('pan_proof',128)->nullable();

            $table->smallInteger('is_hide')->default(0)->comment('0 No 1. Yes')->description('0 No 1. Yes');

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
        Schema::dropIfExists('rental_aggreement');
    }
}
