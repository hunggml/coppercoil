<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Product_Report', function (Blueprint $table) {
            $table->id('ID');
            $table->string('Order_ID')->nullable();
            $table->bigInteger('Product_ID')->nullable();
            $table->bigInteger('Materials_Stock_ID')->nullable();
            $table->float('Quantity')->nullable();
            $table->float('OK')->nullable();
            $table->float('NG')->nullable();
            $table->integer('Status')->nullable();
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
        Schema::dropIfExists('Product_Report');
    }
}
