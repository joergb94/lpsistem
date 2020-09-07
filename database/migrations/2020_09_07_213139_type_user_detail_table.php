<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypeUserDetailTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_user_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mat', 3)->default('TUD');
            $table->unsignedBigInteger('type_user_id')->nullable();
            $table->unsignedBigInteger('data_menu_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            //foreing key 
            $table->foreign('type_user_id')->references('id')->on('type_users')->onDelete('cascade');  
            $table->foreign('data_menu_id')->references('id')->on('data_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::dropIfExists('type_user_details');
       
    }
}
