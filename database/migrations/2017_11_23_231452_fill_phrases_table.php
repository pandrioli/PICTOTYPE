<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FillPhrasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('phrases')->insert([
          ['phrase' => 'EL TIEMPO ES TODO EL TIEMPO'],
          ['phrase' => 'MAS VALE PAJARO EN MANO QUE CIEN VOLANDO'],
          ['phrase' => 'MAS VALE MAÑA QUE FUERZA'],
          ['phrase' => 'MIENTRAS HAY VIDA HAY ESPERANZA'],
          ['phrase' => 'DEL DICHO AL HECHO HAY MUCHO TRECHO'],
          ['phrase' => 'DEL ARBOL CAIDO TODOS HACEN LEÑA'],
          ['phrase' => 'QUIEN SIEMBRA VIENTOS RECOGE TEMPESTADES']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::table('phrases')->delete();
    }
}
