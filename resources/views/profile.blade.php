@extends('base_site')
@section('script', '/js/forms.js')
@section('header-button', 'profile')
@section('content')
      <script type="text/javascript">
        function submit() {
          document.getElementById('profile').submit();
        }
      </script>
      <div class='center-screen'><div class='profile-container'>
        <div class='form-heading back-color-2'>USUARIO: <span>{{$user->username}}</span></div>
        <div class='profile-panel back-color-1'>
          <div class="profile-image avatar"></div>
          <div class="profile-form">
            <form id="profile" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="number" name="user_id" value="{{$user->id}}" hidden>
                <label for="first_name">NOMBRE</label>
                <input class="form-input-text font capitalize" type="text" name="first_name" value="{{old('first_name',$user->first_name)}}">
                <label for="first_name">APELLIDO</label>
                <input class="form-input-text font capitalize" type="text" name="last_name" value="{{old('last_name',$user->last_name)}}">
                <br>FECHA DE REGISTRO: <div style="display: inline-block;">{{date('d-m-Y',strtotime($user->created_at))}}</div>
            </form>
          </div>
        </div>
        <div class="form-button-container">
            <label for="avatar" class="button form-button font back-color-2">IMAGEN</label>
            <input form="profile" type="file" name="avatar" id="avatar" accept="image/*" hidden onchange='submit()'>
            <a class="button form-button back-color-2" href="">ESTADISTICAS</a>
            <button form="profile" type="submit" class="button form-button font back-color-1">
                GUARDAR
            </button>
        </div></div>
      </div>
@endsection
