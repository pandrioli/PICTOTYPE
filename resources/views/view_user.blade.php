@extends('base_site')
@section('content')
      <style>
        #user-avatar {
          background: url({{$user->avatar}});
          background-position: center;
          background-size: cover;
          background-repeat: no-repeat;
        }
      </style>
      <div class='center-screen'><div class='window-container w700'>
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
            <a class="button back-color-2" href="/friends">VOLVER</a>
            <a class="button back-color-2" href="">SOLICITUD ENVIADA</a>
            <a class="button back-color-1" href="">INVITAR</a>
        </div></div>
      </div>
@endsection
