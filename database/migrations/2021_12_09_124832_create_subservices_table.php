<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubservicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    
        Schema::create('subservices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 155);
            $table->text('description');
            $table->double('partialPrice', 11);
            $table->double('price', 11);
            $table->string('image', 155)->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('idService');

            $table->foreign('idService')
            ->references('id')
            ->on('services');
            
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
        Schema::dropIfExists('subservices');
    }
}
