<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdminuser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::table('users', function (Blueprint $table) {
         $table->integer('admin')->default(0);
       });
       DB::table('users')->insert(
         ['username' => 'admin', 'email' => 'admin@pictotype.com', 'password' => bcrypt('123456'), 'admin' => 1]
       );
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
       Schema::table('users', function (Blueprint $table) {
         $table->dropColumn('admin');
       });
     }
}
