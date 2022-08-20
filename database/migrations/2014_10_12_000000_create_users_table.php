<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable();
            $table->string('phone', 55)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at');
            $table->text('password')->nullable();
            $table->string('direction', 155)->nullable();
            $table->text('keyGoogle')->nullable();
            $table->text('keyFacebook')->nullable();
            // 1 admin / 2 cliente
            $table->enum('typeUser', [1, 2])->default(2);
            $table->text('token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
