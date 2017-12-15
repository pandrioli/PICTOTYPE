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
          ['phrase' => 'QUIEN BIEN TE QUIERE TE HARA LLORAR'],
          ['phrase' => 'A PALABRAS NECIAS OIDOS SORDOS'],
          ['phrase' => 'OJOS QUE NO VEN CORAZON QUE NO SIENTE'],
          ['phrase' => 'AL MAL TIEMPO BUENA CARA'],
          ['phrase' => 'A QUIEN MADRUGA DIOS LO AYUDA'],
          ['phrase' => 'EN BOCA CERRADA NO ENTRAN MOSCAS'],
          ['phrase' => 'HOMBRE PREVENIDO VALE POR DOS'],
          ['phrase' => 'A BUEN ENTENDEDOR POCAS PALABRAS'],
          ['phrase' => 'SOBRE GUSTOS NO HAY NADA ESCRITO'],
          ['phrase' => 'NO SOLO DE PAN VIVE EL HOMBRE'],
          ['phrase' => 'NO ES ORO TODO LO QUE RELUCE'],
          ['phrase' => 'MAS VALE SOLO QUE MAL ACOMPAÑADO'],
          ['phrase' => 'LAS ALMAS REPUDIAN TODO ENCIERRO']
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
