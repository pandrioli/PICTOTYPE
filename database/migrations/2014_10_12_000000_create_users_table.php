<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('avatar')->default('/img/profile.png');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert([
          ['username' => 'test1', 'first_name' => 'Pepe', 'last_name' => 'Perez', 'email' => 'test1@pictotype.com', 'password' => bcrypt('asdfasdf')],
          ['username' => 'test2', 'first_name' => 'Juan', 'last_name' => 'Sanchez', 'email' => 'test2@pictotype.com', 'password' => bcrypt('asdfasdf')]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
