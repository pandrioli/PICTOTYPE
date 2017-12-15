@extends('base_site')
@section('script', '/js/form_profile.js')
@section('header-button', 'profile')
@section('content')
      <script type="text/javascript">
        function submit() {
          document.getElementById('profile').submit();
        }
      </script>
      <div class='center-screen'><div class='window-container w700'>
        <div class='window-header back-color-2'>USUARIO: <span>{{$user->username}}</span></div>
        <div class='panel back-color-1'>
          <div class="profile-image avatar"></div>
          <div class="profile-form">
          <div style="display: none;" id="current-country">{{old('country',$user->country)}}</div>
            <form id="profile" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="number" name="user_id" value="{{$user->id}}" hidden>
                <label for="first_name">NOMBRE</label>
                <span class="form-error" id='first_name_error'></span>
                <input id="first_name" class="form-input-text font capitalize" type="text" name="first_name" value="{{old('first_name',$user->first_name)}}">
                <label for="first_name">APELLIDO</label>
                <span class="form-error" id='last_name_error'></span>
                <input id="last_name" class="form-input-text font capitalize" type="text" name="last_name" value="{{old('last_name',$user->last_name)}}">
                <label for="first_name">PAIS</label>
                <select id="country" class="form-input-text font uppercase" type="text" name="country">
                </select>
            </form>
          </div>
        </div>
        <div class="button-container">
            <label for="avatar" class="button font back-color-2">IMAGEN</label>
            <input form="profile" type="file" name="avatar" id="avatar" accept="image/*" hidden onchange='submit()'>
            <a class="button back-color-2" href="/user/friends">AMIGOS</a>
            <button id="save" form="profile" type="submit" class="button font back-color-1">
                GUARDAR DATOS
            </button>
        </div></div>
      </div>
@endsection
