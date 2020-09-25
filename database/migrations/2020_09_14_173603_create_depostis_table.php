<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepostisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('mat', 3)->default('DEP');
            $table->string('bank');
            $table->string('numDep', 20)->nullable;
            $table->string('imageDep')->default('');
            $table->decimal('amount', 6,2);
            $table->string('id_user');
            $table->string('description');            
            $table->integer('status');
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
