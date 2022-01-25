<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommandRepair extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Command_Repair', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',3000)->nullable();
            $table->bigInteger('Mold_ID')->nullable();
            $table->string('Problem',3000)->nullable();
            $table->integer('Status')->nullable();
            $table->string('Type',3000)->nullable();
            $table->integer('level')->nullable();
            $table->string('Note',3000)->nullable();
            $table->bigInteger('User_Repair')->nullable();
            $table->dateTime('Time_Repair')->nullable();
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
        Schema::dropIfExists('Command_Repair');
    }
}
