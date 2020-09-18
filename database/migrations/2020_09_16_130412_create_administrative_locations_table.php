<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrative_locations', function (Blueprint $table) {
            $table->id();
            $table->String('locationName')->unique()->nullable();
            $table->String('locationCode')->unique()->nullable();
            $table->String('supervisor')->nullable();
            $table->integer('status')->nullable()->default(1)->comment="0: Inactive, 1:Active";
            $table->String('doneBy')->nullable();
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
        Schema::dropIfExists('administrative_locations');
    }
}
