<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterGroupMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('Master_Group_Materials', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',3000);
            $table->string('Symbols',3000);
            $table->integer('Quantity')->default(0);
            $table->bigInteger('Unit_ID')->default(0);
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
        Schema::dropIfExists('Master_Group_Materials');
    }
}
