<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idCustomer');
            $table->unsignedBigInteger('idSubservice');
            $table->string('comment', 255);
            //0-1  oculto y visible
            $table->enum('status', [0,1])->default(0);
            $table->double('point', 8)->default(5);

            $table->foreign('idCustomer')
            ->references('id')
            ->on('users');

            $table->foreign('idSubservice')
            ->references('id')
            ->on('subservices');

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
        Schema::dropIfExists('reviews');
    }
}
