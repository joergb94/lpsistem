<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mat', 3)->default('TIC');
            $table->unsignedBigInteger('charged_id')->nullable();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('ticket_type')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('total_seller')->default(0);
            $table->decimal('total_gain')->default(0);
            $table->decimal('total')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('winner')->default(0);
            $table->timestamps();
            $table->softDeletes();

            //foreing key 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('charged_id')->references('id')->on('users')->onDelete('cascade');    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tickets');
        Schema::enableForeignKeyConstraints();
    }
}
