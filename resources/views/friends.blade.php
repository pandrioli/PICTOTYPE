@extends('base_site')
@section('title', 'PICTOTYPE')
@section('script', asset('js/friends.js'))
@php
  $users = $user->friendsAccepted;
  //$users = \App\User::all();
@endphp
@section('content')
  <div id="user-id" hidden>{{ $user->id }}</div>
  <div class="center-screen">
    <div class="window-container w700">
      <div class="tab-container">
        <input id="tab1" type="radio" name="tabs" value="1" hidden checked onchange="switchPanel(0)">
        <label for="tab1" class="tab back-color-1">
          AMIGOS
        </label>
        <input id="tab2" type="radio" name="tabs" value="2" hidden onchange="switchPanel(1)">
        <label for="tab2" class="tab back-color-1">
          BUSCAR USUARIOS
        </label>
      </div>
      <div class="panel switch-container back-color-1">
        <div class="switch-panel">
          <div class="item-list" id="friend-list-container">
            @if ($users->count() > 0)
              @include('includes/user_items')
            @else
              <div style="margin-top: 50px; text-align: center;">
                NO TIENES AMIGOS AUN
              </div>
            @endif
          </div>
        </div>
        <div class="switch-panel">
          <div class="item-list" id="user-list-container">
          </div>
        </div>
      </div>
      <div class="button-container">
        <div class="panel-footer back-color-1">BUSCAR</div>
          <input type="text" id="search" class="button font uppercase back-color-3" autofocus>
      </div>

    </div>
  </div>
@endsection
