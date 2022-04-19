<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExportDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Export_Detail', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Export_ID')->nullable();
            $table->string('Pallet_ID',4000)->nullable();
            $table->string('Box_ID',4000)->nullable();
            $table->bigInteger('Materials_ID')->nullable();
            $table->bigInteger('Warehouse_Detail_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->integer('Status')->nullable();
            $table->integer('Type')->nullable();
            $table->string('Note',4000)->nullable();
            $table->integer('STT')->nullable();
            $table->dateTime('Time_Export')->nullable();
            $table->bigInteger('User_Created')->nullable();
            $table->dateTime('Time_Created')->nullable();
            $table->bigInteger('User_Updated')->nullable();
            $table->dateTime('Time_Updated')->nullable();
            $table->boolean('IsDelete')->default(0);
            $table->integer('Transfer')->default(0)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Export_Detail');
    }
}
