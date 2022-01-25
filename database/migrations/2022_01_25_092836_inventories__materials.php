<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventoriesMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Inventories_Materials', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Command_Inventories_ID')->nullable();
            $table->bigInteger('Warehouse_System_ID')->nullable();
            $table->string('Pallet_System_ID',3000)->nullable();
            $table->bigInteger('Materials_System_ID')->default(0)->nullable();
            $table->string('Box_System_ID',3000)->default(0)->nullable();
            $table->float('Quantity_System')->nullable();
            $table->dateTime('Time_Import_System')->nullable();
            $table->string('Box_ID',3000)->nullable();
            $table->float('Quantity')->nullable();
            $table->integer('Status')->default(1)->nullable();
            $table->integer('Type')->nullable();
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
        Schema::dropIfExists('Inventories_Materials');
    }
}
