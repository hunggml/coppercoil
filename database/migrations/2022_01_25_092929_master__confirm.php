<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Confirm', function (Blueprint $table) {
            $table->id('ID');
            $table->integer('Step')->nullable();
            $table->integer('User_level')->nullable();
            $table->string('Group')->nullable();
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
        Schema::dropIfExists('Master_Confirm');
    }
}
