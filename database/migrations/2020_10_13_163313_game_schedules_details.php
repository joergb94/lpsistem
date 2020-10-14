<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GameSchedulesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_schedules_details', function (Blueprint $table) {
            $table->id();
            $table->string('mat', 3)->default('GSD');
            $table->unsignedBigInteger('game_schedule_id')->nullable();
            $table->string('number_win',5)->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //foreing key 
            $table->foreign('game_schedule_id')->references('id')->on('game_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_schedules_details');
    }
}
