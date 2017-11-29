<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('practique');
            $table->integer('mode');
            $table->integer('time_per_letter');
            $table->integer('phrase_id')->unsigned();
            $table->integer('state')->default(0);
            $table->integer('winner_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('phrase_id')->references('id')->on('phrases');
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
        Schema::drop('games');
    }
}
