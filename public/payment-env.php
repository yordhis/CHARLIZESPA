<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 55)->unique();
            $table->unsignedBigInteger('idCustomer');
            $table->unsignedBigInteger('idSubservice');
            $table->string('date', 55);
            $table->double('pay', 11);
            $table->double('pendingPay', 11)->nullable();
            $table->string('payReference', 100)->nullable();
            $table->unsignedBigInteger('idTypepayment');
            $table->unsignedBigInteger('idQuote');

            $table->foreign('idCustomer')
            ->references('id')
            ->on('users');

            $table->foreign('idSubservice')
            ->references('id')
            ->on('subservices');

            $table->foreign('idTypepayment')
            ->references('id')
            ->on('typepayments');

            $table->foreign('idQuote')
            ->references('id')
            ->on('quotes');

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
        Schema::dropIfExists('payments');
    }
}
