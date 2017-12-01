@extends('base_site')
@section('title', 'PICTOTYPE')
@section('script', asset('js/friends.js'))

@section('content')
  <div id="user-id" hidden>{{ $user->id }}</div>
  <div class="center-screen">
    <div class="home-user-container">
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
      <div class="switch-container back-color-1">
        <div class="switch-panel">
          <div class="item-list">
          </div>
        </div>
        <div class="switch-panel">
          <div class="item-list">
          </div>
        </div>
      </div>
      <div class="button-container">
      </div>
    </div>
  </div>
@endsection
