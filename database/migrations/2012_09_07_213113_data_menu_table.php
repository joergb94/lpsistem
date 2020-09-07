<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataMenuTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('data_menus');
        Schema::create('data_menus', function (Blueprint $table) {
            $table->id();
            $table->string('mat', 3)->default('BAM');
            $table->string('name', 100);
            $table->string('icon', 100);
            $table->string('link', 100);
            $table->string('prioridad', 10);
            $table->boolean('active')->default(1);
           
            $table->timestamps();
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
        Schema::dropIfExists('data_menus');
        Schema::enableForeignKeyConstraints();
        
    }
}
