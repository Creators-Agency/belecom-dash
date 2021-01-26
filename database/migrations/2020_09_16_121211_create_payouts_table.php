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
            $table->String('solarSerialNumber')->comment="solar panel";
            $table->String('clientNames');
            $table->String('clientID');
            $table->String('clientPhone')->comment="tel used paying";
            $table->String('monthYear');
            $table->biginteger('payment');
            $table->integer('loanStatus')->default(0)->comment="0: still in paying process, 1: payment is done";
            $table->String('transactionID');
            $table->String('status');
            $table->String('accountStatus')->default(0)->comment="0: activate, 1: Account is deactivated";
            $table->String('balance');
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