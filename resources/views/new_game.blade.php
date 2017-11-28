@extends('base_site')
@section('script', asset('js/available.js'))
@section('content')
  <div class="center-screen">
    <div class="form-container">
      <div class="form-heading back-color-2">
        SELECCIONE MODO DE JUEGO
      </div>
      <a href="/joinpublicgame/0" class="button game-mode-button back-color-1">
        <div class="game-mode-label">
          MEJOR TIEMPO
        </div>
        <div id="mode0-available">
        </div>
      </a>
      <a href="/joinpublicgame/1" class="button game-mode-button back-color-1">
        <div class="game-mode-label">
          PUNTAJE
        </div>
        <div id="mode1-available">
        </div>
      </a>
      <div class="form-button-container">
        <a class="button form-button back-color-2" href="/">CANCELAR</a>
        <a class="button form-button back-color-1" href="/creategame/real">CREAR NUEVA PARTIDA</a>
      </div>
    </div>
  </div>
@endsection
