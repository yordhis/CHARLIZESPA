<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idSubservice')->nullable();
            $table->unsignedBigInteger('idCustomer')->nullable();
            $table->string('time', 55)->nullable();
            $table->string('date', 55)->nullable();
            //los estatus son: Por atender, atendida, calificada 0-1-2
            $table->enum('status', [0,1,2])->default(0);

            
            $table->foreign('idSubservice')
            ->references('id')
            ->on('subservices');
            
            $table->foreign('idCustomer')
            ->references('id')
            ->on('users');

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
        Schema::dropIfExists('quotes');
    }
}
