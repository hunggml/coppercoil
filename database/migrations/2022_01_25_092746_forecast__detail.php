<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForecastDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('Forecast_Detail', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('FC_ID')->nullable();
            $table->bigInteger('Product_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->string('Note',4000)->nullable();
            $table->integer('Status')->default(0)->nullable();
            $table->bigInteger('User_Created')->nullable();
            $table->dateTime('Time_Created')->nullable();
            $table->bigInteger('User_Updated')->nullable();
            $table->dateTime('Time_Updated')->nullable();
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
        Schema::dropIfExists('Forecast_Detail');
    }
}
