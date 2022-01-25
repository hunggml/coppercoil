<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Materials', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name');
            $table->string('Symbols');
            $table->integer('Unit_ID')->default(0)->nullable();
            $table->integer('Packing_ID')->default(0)->nullable();
            $table->integer('Supplier_ID')->default(0)->nullable();
            $table->string('Model')->nullable();
            $table->float('Standard_Unit')->default(0)->nullable();
            $table->float('Standard_Packing')->default(0)->nullable();
            $table->string('Part_ID')->nullable();
            $table->bigInteger('Warehouse_ID')->default(0);
            $table->string('Note',3000)->nullable();
            $table->float('Lead_Time')->nullable();
            $table->integer('Export_Type')->nullable();
            $table->float('Preparation_Time')->nullable();
            $table->string('Spec',3000)->nullable();
            $table->float('Difference')->nullable();
            $table->string('Wire_Type',3000)->nullable();
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
        Schema::dropIfExists('Master_Materials');
    }
}
