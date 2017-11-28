<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type');
            $table->string('message');
            $table->integer('user_id')->unsigned();
            $table->integer('game_id')->unsigned()->nullable();
            $table->integer('sender_id')->unsigned()->nullable();
            $table->boolean('read')->default(false);
            $table->boolean('clickeable')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('notifications');
    }
}
