<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Warehouse', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name');
            $table->string('Symbols');
            $table->bigInteger('Quantity_Rows')->default(0);
            $table->bigInteger('Quantity_Columns')->default(0);
            $table->string('MAC')->nullable();
            $table->float('Quantity_Unit')->nullable();
            $table->bigInteger('Unit_ID')->default(0)->nullable();
            $table->float('Quantity_Packing')->nullable();
            $table->bigInteger('Packing_ID')->default(0)->nullable();
            $table->bigInteger('Group_Materials_ID')->default(0)->nullable();
            $table->integer('Floor')->default(1)->nullable();
            $table->string('Area',3000)->nullable();
            $table->string('Note',3000)->nullable();
            $table->integer('Accept')->default(0)->nullable();
            $table->string('Email',3000)->nullable();
            $table->string('Email2',3000)->nullable();
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
        Schema::dropIfExists('Master_Warehouse');
    }
}
