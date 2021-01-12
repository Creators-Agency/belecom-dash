<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->String('firstname');
            $table->String('lastname');
            $table->String('identification')->unique();
            $table->String('gender')->nullable();
            $table->date('DOB')->nullable();
            $table->biginteger('primaryPhone')->unique();
            $table->biginteger('secondaryPhone')->nullable();
            $table->String('educationLevel')->nullable();
            $table->String('incomeSource')->nullable();
            $table->String('sourceOfEnergy')->nullable();
            $table->String('location')->nullable();
            $table->String('village')->nullable();
            $table->String('quarterName')->nullable();
            $table->integer('houseNumber')->nullable();
            $table->String('buildingMaterial')->nullable();
            $table->integer('familyMember')->nullable();
            $table->integer('membersInSchool')->nullable();
            $table->integer('U18Male')->nullable();
            $table->integer('U17Male')->nullable();
            $table->integer('U18Female')->nullable();
            $table->integer('U17Female')->nullable();
            $table->integer('employmentStatus')->default(0)->comment="0: Un-employed, 1: Employed";
            $table->String('referredby')->nullable();
            $table->integer('isActive')->default(1)->comment="0: Deleted, 1:Active, 3:assigned";
            $table->integer('doneBy');
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
        Schema::dropIfExists('beneficiaries');
    }
}