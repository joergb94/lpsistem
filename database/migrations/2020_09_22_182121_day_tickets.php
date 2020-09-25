<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DayTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('mat', 3)->default('DaT');
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->unsignedBigInteger('day_id')->nullable();
            $table->date('game_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //foreing key 
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_tickets');
    }
}
