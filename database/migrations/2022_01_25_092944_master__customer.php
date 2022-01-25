<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('Master_Customer', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name',2000);
            $table->string('Symbols',2000)->nullable();
            $table->string('Note',2000)->nullable();
            $table->string('Address',2000)->nullable();
            $table->string('Contact',2000)->nullable();
            $table->string('Phone',2000)->nullable();
            $table->string('Tax_Code',2000)->nullable();
            $table->string('Email',2000)->nullable();
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
        Schema::dropIfExists('Master_Customer');
    }
}
