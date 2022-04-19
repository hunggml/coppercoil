<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommandImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Command_Import', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',4000)->nullable();
            $table->string('Note',4000)->nullable();
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
        Schema::dropIfExists('Command_Import');
    }
}
