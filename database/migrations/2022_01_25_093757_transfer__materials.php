<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransferMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Transfer_Materials', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Export_ID')->nullable();
            $table->bigInteger('Export_Detail_ID')->nullable();
            $table->string('Pallet_ID',3000)->nullable();
            $table->string('Box_ID',3000)->nullable();
            $table->bigInteger('Materials_ID')->nullable();
            $table->bigInteger('Warehouse_Detail_ID_Go')->nullable();
            $table->bigInteger('Warehouse_Detail_ID_To')->nullable();
            $table->float('Quantity')->nullable();
            $table->string('Status')->nullable();
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
        Schema::dropIfExists('Transfer_Materials');
    }
}
