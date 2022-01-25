<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MaterialsRevision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Materials_Revision', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Materials_ID')->nullable();
            $table->bigInteger('Version')->nullable();
            $table->string('Name')->nullable();
            $table->string('Symbols')->nullable();
            $table->bigInteger('Unit_ID')->nullable();
            $table->bigInteger('Packing_ID')->nullable();
            $table->bigInteger('Supplier_ID')->nullable();
            $table->string('Model')->nullable();
            $table->float('Standard_Unit')->nullable();
            $table->float('Standard_Packing')->nullable();
            $table->string('Part_ID')->nullable();
            $table->bigInteger('Note')->nullable();
            $table->string('Lead_Time',3000)->nullable();
            $table->integer('Export_Type')->default(0)->nullable();
            $table->integer('Preparation_Time')->default(0)->nullable();
            $table->string('Spec',3000)->nullable();
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
        Schema::dropIfExists('Materials_Revision');
    }
}
