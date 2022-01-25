<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommandMaintance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Command_Maintance', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Mold_ID')->nullable();
            $table->integer('level')->nullable();
            $table->integer('Status')->nullable();
            $table->string('Note',5000)->nullable();
            $table->dateTime('Time_Maintance')->nullable();
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
        Schema::dropIfExists('Command_Maintance');
    }
}
