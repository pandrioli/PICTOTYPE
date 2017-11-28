@extends('base_site')
@if ($practique)
  @section('header-button','practique')
@endif
@section('content')
  <div class="center-screen">
    <div class='form-container'>
      <div class='form-heading back-color-2'>CREAR NUEVA PARTIDA</div>
      <div class="form-body back-color-1">
        <form class="font" action="/creategame" method="post" id="create-game" style="text-align: center;">
          {{ csrf_field() }}
          <input type="text" name="practique" value="{{ $practique }}" hidden>
          MODO:
          <input hidden type="radio" class="form-radio" name="mode" id="mode0" value="0" checked>
          <label for="mode0" class="form-option">MEJOR TIEMPO</label>
          <input hidden type="radio" class="form-radio" name="mode" id="mode1" value="1">
          <label for="mode1" class="form-option">PUNTAJE</label>
          <div class="hidden-options">
            SEG. POR LETRA:
            <input hidden type="radio" class="form-radio" name="time-per-letter" id="time10" value="10" checked>
            <label for="time10" class="form-option">10</label>
            <input hidden type="radio" class="form-radio" name="time-per-letter" id="time15" value="15">
            <label for="time15" class="form-option">15</label>
            <input hidden type="radio" class="form-radio" name="time-per-letter" id="time20" value="20">
            <label for="time20" class="form-option">20</label>
          </div>
          <div class="pretty p-switch p-fill"
            @if ($practique)
              style="display: none;"
            @endif
            >
            <input type="checkbox" name="play_now" checked
            />
            <div class="state p-success">
                <label>JUGAR AHORA</label>
            </div>
        </div>
        </form>
      </div>
      <div class="form-button-container">
        <a class="button form-button back-color-2" href="{{ url('/') }}">CANCELAR</a>
        <button form="create-game" type="submit" class="button form-button font back-color-1">
            CREAR PARTIDA
        </button>
      </div>
    </div>
  </div>
@endsection
