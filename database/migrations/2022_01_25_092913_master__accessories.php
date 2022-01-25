<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterAccessories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Accessories', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',3000)->nullable();
            $table->string('Symbols',3000)->nullable();
            $table->string('Note',3000)->nullable();
            $table->string('Symbols_Input',3000)->nullable();
            $table->float('Height_Use')->nullable();
            $table->string('Image',3000)->nullable();
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
        Schema::dropIfExists('Master_Accessories');
    }
}
