@extends('base_site')
@section('title', 'PICTOTYPE')
@section('header-button', 'home')
@section('script', asset('js/home_user.js'))
@section('content')
  <div id="timestamp" hidden>{{ $timestamp }}</div>
  <div id="user-id" hidden>{{ $user->id }}</div>
  <div class="center-screen">
    <div class="home-user-container">
      <div class="form-heading back-color-2">
        USUARIO: <span>{{$user->username}}</span>
      </div>
      <div class="tab-container">
        <input id="tab1" type="radio" name="tabs" value="1" hidden checked onchange="switchPanel(0)">
        <label for="tab1" class="tab back-color-1">
          EN CURSO
        </label>
        <input id="tab2" type="radio" name="tabs" value="2" hidden onchange="switchPanel(1)">
        <label for="tab2" class="tab back-color-1">
          FINALIZADAS
        </label>
        <input id="tab3" type="radio" name="tabs" value="3" hidden onchange="switchPanel(2)">
        <label for="tab3" class="tab back-color-1">
          <i class="fa fa-bell" aria-hidden="true"></i>
          <div class="notification-number">1</div>
        </label>
      </div>
      <div class="switch-container back-color-1">
        <div class="switch-panel">
          <div class="item-list">
            @if ($user->gamesToPlay->count() + $user->gamesPlayed->count() == 0)
              <div style="text-align: center; margin-top: 100px;">NO HAY PARTIDAS EN CURSO</div>
            @endif
            @if ($user->gamesToPlay->count()>0)
              <div class="games-list-header">PARTIDAS PENDIENTES DE JUGAR</div>
            @endif
            @foreach ($user->gamesToPlay as $key=>$game)
              @include('game_item')
            @endforeach
            @if ($user->gamesPlayed->count()>0)
              <div class="games-list-header">ESPERANDO QUE JUEGUE EL OPONENTE</div>
            @endif
            @foreach ($user->gamesPlayed as $key=>$game)
              @include('game_item')
            @endforeach
          </div>
        </div>
        <div class="switch-panel">
          <div class="item-list">
            @forelse ($user->gamesFinished as $key=>$game)
              @include('game_item')
            @empty
                <div style="text-align: center; margin-top: 100px;">NO HAY PARTIDAS FINALIZADAS</div>
            @endforelse
          </div>
        </div>
        <div class="switch-panel">
          <div class="games-list-header">NOTIFICACIONES RECIBIDAS</div>
          <div class="item-list">
            @forelse ($user->notifications as $key=>$notification)
              @include('notification_item')
            @empty
                <div style="text-align: center; margin-top: 100px;">NO HAY NOTIFICACIONES</div>
            @endforelse
          </div>
        </div>
      </div>
      <div class="form-button-container">
        <div class="panel-footer back-color-1">NUEVA PARTIDA</div>
        <a class="button form-button back-color-2" href="">PRIVADA</a>
        <a class="button form-button back-color-1" href="/newgame">PUBLICA</a>
      </div>
    </div>
  </div>
@endsection
