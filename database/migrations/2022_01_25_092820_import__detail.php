<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Import_Detail', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Command_ID')->nullable();
            $table->bigInteger('Materials_ID')->nullable();
            $table->string('Box_ID',3000)->nullable();
            $table->string('Case_No',3000)->nullable();
            $table->string('Lot_No',3000)->nullable();
            $table->string('Pallet_ID',3000)->nullable();
            $table->float('Quantity')->nullable();
            $table->date('Packing_Date')->nullable();
            $table->bigInteger('Warehouse_Detail_ID')->nullable();
            $table->float('Inventory')->nullable();
            $table->integer('Status')->nullable();
            $table->integer('Type')->nullable();
            $table->dateTime('Time_Import')->nullable();
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
        Schema::dropIfExists('Import_Detail');
    }
}
