<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MaintanceDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Maintance_Detail', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Maintance_ID')->nullable();
            $table->bigInteger('Mold_ID')->nullable();
            $table->bigInteger('Accessories_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->string('Note',5000)->nullable();
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
        Schema::dropIfExists('Maintance_Detail');
    }
}
