<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Master_Supplier', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Name');
            $table->string('Symbols');
            $table->string('Address',3000)->nullable();
            $table->string('Contact',3000)->nullable();
            $table->string('Phone',3000)->nullable();
            $table->string('Tax_Code',3000)->nullable();
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
        Schema::dropIfExists('Master_Supplier');
    }
}
