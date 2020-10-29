<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketDetailsTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mat', 3)->default('TD');
            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->integer('figures')->nullable();
            $table->string('game_number',5)->nullable();
            $table->decimal('bet')->nullable();
            $table->decimal('bet_seller')->nullable();
            $table->decimal('bet_gain')->nullable();
            $table->decimal('prize')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('winner')->default(0);
            $table->timestamps();
            $table->softDeletes();

            //foreing key 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
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
        Schema::dropIfExists('ticket_details');
    }
}
