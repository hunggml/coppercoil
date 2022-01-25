<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class POMoldDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PO_Mold_Detail', function (Blueprint $table) {
            $table->id('ID');
            $table->bigInteger('PO_Mold_ID')->nullable();
            $table->bigInteger('Accessories_ID')->nullable();
            $table->integer('Quantity')->nullable();
            $table->string('Petitioner')->nullable();
            $table->dateTime('Time_Required')->nullable();
            $table->dateTime('Time_Order')->nullable();
            $table->string('User_Order')->nullable();
            $table->dateTime('Expected_Return_Time')->nullable();
            $table->dateTime('Delivery_Time')->nullable();
            $table->string('Receiver')->nullable();
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
        Schema::dropIfExists('PO_Mold_Detail');
    }
}
