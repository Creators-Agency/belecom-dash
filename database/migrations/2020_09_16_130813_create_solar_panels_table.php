<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarPanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_panels', function (Blueprint $table) {
            $table->id();
            $table->String('solarPanelSerialNumber')->unique();
            $table->integer('solarPanelType');
            $table->integer('location');
            $table->integer('status')->default(0)->comment="0: Pending, 1: Sold";
            $table->integer('moreInfo')->default(0)->comment="0: normalState, 1: Returned, 2: Faulty, 3: undermaintenance, 4: Stolen";
            $table->integer('doneBy')->nullable();
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
        Schema::dropIfExists('solar_panels');
    }
}