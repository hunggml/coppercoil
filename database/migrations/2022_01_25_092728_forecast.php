<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Forecast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('Forecast', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',4000)->nullable();
            $table->string('Note',4000)->nullable();
            $table->integer('Status')->nullable();
            $table->integer('Month')->nullable();
            $table->integer('Year')->nullable();
            $table->bigInteger('User_Created')->nullable();
            $table->dateTime('Time_Created')->nullable();
            $table->bigInteger('User_Updated')->nullable();
            $table->dateTime('Time_Updated')->nullable();
            $table->bigInteger('User_Accept')->nullable();
            $table->dateTime('Time_Accept')->nullable();
            $table->boolean('IsDelete')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Forecast');
    }
}
