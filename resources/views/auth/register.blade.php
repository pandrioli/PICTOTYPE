@extends('base_site')
@section('script', '/js/form_register.js')
@section('header-button', 'register')
@section('content')
      <div class="center-screen">
        <div class="window-container w500">
          <div class="window-header back-color-2">REGISTRO DE USUARIO</div>
          <div class="panel back-color-1">
              <form role="form" method="POST" action="{{ url('/register') }}" id="register_form">
                  {{ csrf_field() }}

                      <label for="name">NOMBRE DE USUARIO</label>
                      <span class="form-error" id="error-username">
                      @if ($errors->has('username'))
                              <strong>{{ $errors->first('username') }}</strong>
                      @endif
                      </span>

                      <input id="username" type="text" class="form-input-text font" name="username" value="{{ old('username') }}" autofocus>


                      <label for="email">E-MAIL</label>
                      <span class="form-error" id="error-email">
                      @if ($errors->has('email'))
                              <strong>{{ $errors->first('email') }}</strong>
                      @endif
                      </span>

                      <input id="email" type="email" class="form-input-text font" name="email" value="{{ old('email') }}">


                      <label for="password">PASSWORD</label>
                      <span class="form-error" id='error-pass'>
                      @if ($errors->has('password'))
                              <strong>{{ $errors->first('password') }}</strong>
                      @endif
                      </span>

                      <input id="password" type="password" class="form-input-text font" name="password">


                      <label for="password-confirm">CONFIRMAR PASS</label>
                      <span class="form-error" id='error-confirm'>
                      @if ($errors->has('password_confirmation'))
                              <strong>{{ $errors->first('password_confirmation') }}</strong>
                      @endif
                      </span>

                      <input id="password-confirm" type="password" class="form-input-text font" name="password_confirmation">


              </form>
          </div>
          <div class="button-container">
            <a class="button form-button back-color-2" href="/">CANCELAR</a>
            <button id="register" type="submit" class="button form-button back-color-1 font" form="register_form">
                REGISTRARSE
            </button>
          </div>
      </div>
    </div>
@endsection
