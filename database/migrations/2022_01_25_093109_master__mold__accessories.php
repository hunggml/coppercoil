<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MasterMoldAccessories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('Master_Mold_Accessories', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('Mold_ID')->nullable();
            $table->bigInteger('Accessories_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->float('Height_Not_Use')->nullable();
            $table->dateTime('Time_Created')->nullable();
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
        Schema::dropIfExists('Master_Mold_Accessories');
    }
}
