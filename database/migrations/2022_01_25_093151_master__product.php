<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Product', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name');
            $table->string('Symbols');
            $table->bigInteger('Unit_ID')->default(0);
            $table->bigInteger('Packing_ID')->nullable();
            $table->float('Packing_Standard')->nullable();
            $table->float('Price')->nullable();
            $table->integer('Export_Type')->nullable();
            $table->integer('Type')->nullable();
            $table->string('Spec',3000)->nullable();
            $table->bigInteger('Materials_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->string('Note',3000)->nullable();
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
        Schema::dropIfExists('Master_Product');
    }
}
