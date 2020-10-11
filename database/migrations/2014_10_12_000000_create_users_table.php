<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->string('photo')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('gender')->nullable();
            $table->string('DOB')->nullable();
            $table->string('nationalID')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('access_key')->nullable()->comment="generated for recovering option";
            $table->string('password');
            $table->integer('type')->default(0)->comment="0: simple | 1: Dev";
            $table->integer('status')->comment="0: Disabled | 1: Active";
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}