<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarPanelTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_panel_types', function (Blueprint $table) {
            $table->id();
            $table->String('solarTypeName')->nullable();
            $table->integer('price')->nullable();
            $table->integer('isActive')->nullable()->default(0)->comment="0: Inactive, 1: Active";
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
        Schema::dropIfExists('solar_panel_types');
    }
}
