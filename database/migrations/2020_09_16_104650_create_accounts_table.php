<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->biginteger('beneficiary')->unique();
            $table->biginteger('productNumber')->unique();
            $table->String('clientNames');
            $table->integer('monthPaid')->nullable()->comment="Month a clients has paid";
            $table->integer('loan')->default(0)->comment="Total Amount of Loan he/she has";
            $table->String('monthleft')->default(0)->comment="Amount left to be paid";
            $table->String('isActive')->default(1)->comment="0: Inactive, 1: Active, 2: Deleted";
            $table->biginteger('loanPeriod')->default(36)->comment="charges to be added for late payments";
            $table->biginteger('charges')->default(0)->comment="charges to be added for late payments";
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
        Schema::dropIfExists('accounts');
    }
}