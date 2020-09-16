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
            $table->String('clientAccount');
            $table->integer('amountpaid')->nullable();
            $table->integer('monthpaid')->nullable();
            $table->String('transactionID');
            $table->String('status');
            $table->String('doneBy');
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
