<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExportMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Export_Materials', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Materials_ID')->nullable();
            $table->bigInteger('Go')->nullable();
            $table->float('Quantity')->nullable();
            $table->integer('Count')->nullable();
            $table->tinyInteger('Type')->nullable();
            $table->integer('Status')->nullable();
            $table->bigInteger('To')->nullable();
            $table->string('Note',4000)->nullable();
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
        Schema::dropIfExists('Export_Materials');
    }
}
