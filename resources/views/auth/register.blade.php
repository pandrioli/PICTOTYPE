@extends('base_site')
@section('script', '/js/forms.js')
@section('header-button', 'register')
@section('content')
      <div class="center-screen">
        <div class="form-container">
          <div class="form-heading back-color-2">REGISTRO DE USUARIO</div>
          <div class="form-body back-color-1">
              <form role="form" method="POST" action="{{ url('/register') }}" id="register_form">
                  {{ csrf_field() }}

                      <label for="name">NOMBRE DE USUARIO</label>
                      @if ($errors->has('username'))
                          <span class="form-error">
                              <strong>{{ $errors->first('username') }}</strong>
                          </span>
                      @endif

                          <input id="username" type="text" class="form-input-text font" name="username" value="{{ old('username') }}" autofocus>


                      <label for="email">E-MAIL</label>
                      @if ($errors->has('email'))
                          <span class="form-error">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif

                          <input id="email" type="email" class="form-input-text font" name="email" value="{{ old('email') }}">


                      <label for="password">PASSWORD</label>
                      @if ($errors->has('password'))
                          <span class="form-error">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif

                          <input id="password" type="password" class="form-input-text font" name="password">


                      <label for="password-confirm">CONFIRMAR PASS</label>
                      @if ($errors->has('password_confirmation'))
                          <span class="form-error">
                              <strong>{{ $errors->first('password_confirmation') }}</strong>
                          </span>
                      @endif

                          <input id="password-confirm" type="password" class="form-input-text font" name="password_confirmation">


              </form>
          </div>
          <div class="form-button-container">
            <a class="button form-button back-color-2" href="/">CANCELAR</a>
            <button type="submit" class="button form-button back-color-1 font" form="register_form">
                REGISTRARSE
            </button>
          </div>
      </div>
    </div>
@endsection
