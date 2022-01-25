<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Role extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id('ID');
            $table->string('role');
            $table->string('description');
            $table->string('note',3000)->nullable();
            $table->string('software',3000)->nullable();
            $table->bigInteger('user_created')->nullable();
            $table->dateTime('time_created')->nullable();
            $table->bigInteger('user_updated')->nullable();
            $table->dateTime('time_updated')->nullable();
            $table->boolean('isdelete')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
