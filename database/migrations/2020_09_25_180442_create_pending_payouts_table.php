<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_payouts', function (Blueprint $table) {
            $table->id();
            $table->String('solarSerialNumber')->comment="solar panel";
            $table->String('clientNames');
            $table->String('clientID');
            $table->String('clientPhone');
            $table->string('monthYear');
            $table->integer('payment');
            $table->integer('balance');
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
        Schema::dropIfExists('pending_payouts');
    }
}
