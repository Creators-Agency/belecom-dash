<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->String('solarSerialNumber')->comment="solar panel type";
            $table->String('clientNames');
            $table->String('clientID');
            $table->String('clientPhone');
            $table->integer('monthYear')->nullable();
            $table->integer('payment')->nullable();
            $table->integer('balance')->nullable();
            $table->String('transactionID');
            $table->String('status');
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
        Schema::dropIfExists('payouts');
    }
}
