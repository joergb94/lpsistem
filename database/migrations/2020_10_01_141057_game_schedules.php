<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GameSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('mat', 3)->default('GS');
            $table->string('name')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->date('date')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            //foreing key 
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_schedules');
    }
}
