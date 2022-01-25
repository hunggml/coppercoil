<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PO extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PO', function (Blueprint $table) {
            $table->id('ID');
            $table->string('PO_Name',3000)->nullable();
            $table->bigInteger('Materials_ID')->nullable();
            $table->bigInteger('Product_ID')->nullable();
            $table->bigInteger('Machine_ID')->nullable();
            $table->bigInteger('Tool_ID')->nullable();
            $table->bigInteger('Replace_ID')->nullable();
            $table->date('Day')->nullable();
            $table->float('Quantity')->nullable();
            $table->string('Note',3000)->nullable();
            $table->float('Owe')->nullable();
            $table->integer('Type')->nullable();
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
        Schema::dropIfExists('PO');
    }
}
