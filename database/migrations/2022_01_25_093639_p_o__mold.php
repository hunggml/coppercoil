<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class POMold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('PO_Mold', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Mold_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->dateTime('Time_PO')->nullable();
            $table->integer('Type')->default(0)->nullable();
            $table->integer('Status')->default(0)->nullable();
            $table->bigInteger('Supplier_ID')->nullable();
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
        Schema::dropIfExists('PO_Mold');
    }
}
