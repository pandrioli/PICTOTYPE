<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGamesUsersStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('games_users', function (Blueprint $table) {
        $table->float('letter_average');
      });
      Schema::table('users_stats', function (Blueprint $table) {
        $table->float('wins_ratio')->nullable();
        $table->integer('ranking1')->nullable();
        $table->integer('ranking2')->nullable();
        $table->integer('ranking3')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('games_users', function (Blueprint $table) {
        $table->dropColumn('letter_average');
      });
      Schema::table('users_stats', function (Blueprint $table) {
        $table->dropColumn('wins_ratio');
        $table->dropColumn('ranking1');
        $table->dropColumn('ranking2');
        $table->dropColumn('ranking3');
      });
    }
}
