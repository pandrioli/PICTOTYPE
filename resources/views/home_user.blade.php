@extends('base_site')
@section('title', 'PICTOTYPE')
@section('header-button', 'home')
@section('script', asset('js/home_user.js'))
@php
  $notifications = $user->notifications;
@endphp
@section('content')
  <div id="timestamp" hidden>{{ $timestamp }}</div>
  <div id="user-id" hidden>{{ $user->id }}</div>
  <div class="center-screen">
    <div class="window-container w700">
      <div class="home-header">
        <div class="home-username back-color-1">
          {{$user->username}}
        </div>
        <a class="home-stats back-color-2">ESTADISTICAS</a>
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
          <div class="notification-alert" id="notif-alert" style="display: none;"></div>
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
          <div class="item-list" id="notifications-container">
              @if ($notifications->count() > 0)
              @include('notification_items')
            @else
              <div style="text-align: center; margin-top: 100px;">NO HAY NOTIFICACIONES</div>
            @endif
          </div>
        </div>
      </div>
      <div class="button-container">
        <div class="panel-footer back-color-1">NUEVA PARTIDA</div>
        <a class="button back-color-2" href="/friends">PRIVADA</a>
        <a class="button back-color-2" href="/newgame">PUBLICA</a>
      </div>
    </div>
  </div>
@endsection
