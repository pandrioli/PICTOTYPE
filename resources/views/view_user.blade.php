@extends('base_site')
@section('content')
@php
  use App\User;
  $friendship = \Auth::user()->friendship($user->id);
  if ($back == "home") $backURL = '/';
  if ($back == "friends") $backURL = '/user/friends';
  if (substr($back, 0, 4) == "game") $backURL = '/game/view/'.substr($back, 5);
@endphp
      <style>
        #user-avatar {
          background: url({{$user->avatar}});
          background-position: center;
          background-size: cover;
          background-repeat: no-repeat;
        }
      </style>
      <div class='center-screen'>
        <div class='window-container w700'>
          <div class='window-header back-color-2'>USUARIO: <span>{{$user->username}}</span></div>
          <div class='panel back-color-1'>
            <div class="profile-image" id="user-avatar"></div>
            <div class="panel">
              @if ($user->fullName())
                NOMBRE: {{ $user->fullName() }}
              @endif
            </div>
          </div>
          <div class="button-container">
              @if ($friendship == User::FRIENDSHIP_NONE)
                <a class="button back-color-2" href="/user/friends/request/{{$user->id}}/{{$back}}">SOLICITAR AMISTAD</a>
              @endif
              @if ($friendship == User::FRIENDSHIP_REQUESTED)
                <a class="button back-color-3" href="">AMISTAD SOLICITADA</a>
              @endif
              @if ($friendship == User::FRIENDSHIP_PENDING)
                <a class="button back-color-2" href="/user/friends/accept/{{$user->id}}/{{$back}}">ACEPTAR AMISTAD</a>
              @endif
              @if ($friendship == User::FRIENDSHIP_ACCEPTED)
                <a class="button back-color-2" href="/user/friends/cancel/{{$user->id}}/{{$back}}">CANCELAR AMISTAD</a>
                <a class="button back-color-1" href="/game/create/{{$user->id}}">JUGAR PARTIDA</a>
              @else
                <a class="button back-color-1" href="/game/create/{{$user->id}}">INVITAR PARTIDA</a>
              @endif
          </div>
          <a class="button back-button back-color-1" href="{{$backURL}}"><i class="fa fa-backward" aria-hidden="true"></i>&nbspVOLVER</a>
      </div>
    </div>
@endsection