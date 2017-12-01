@extends('base_site')
@section('script', asset('js/forms.js'))
@section('header-button', 'login')
@section('content')
            <div class="center-screen">
              <div class="window-container w500">
                  <div class="window-header back-color-2">INGRESO AL SISTEMA</div>
                  <div class="panel back-color-1">
                      <form id="login" class="font" role="form" method="POST" action="{{ url('/login') }}">
                          {{ csrf_field() }}

                              <label for="username" >NOMBRE DE USUARIO</label>
                              @if ($errors->has('username'))
                                  <span class="form-error">
                                      <strong>{{ $errors->first('username') }}</strong>
                                  </span>
                              @endif

                              <input id="username" type="text" class="form-input-text font" name="username" value="{{ old('email') }}" autofocus>

                              <label for="password">PASSWORD</label>
                              @if ($errors->has('password'))
                                  <span class="form-error">
                                      <strong>{{ $errors->first('password') }}</strong>
                                  </span>
                              @endif

                              <input id="password" type="password" class="form-input-text font" name="password">
                              <br>
                              <div class="pretty p-switch p-fill">
                                <input type="checkbox" name="remember"/>
                                <div class="state p-success">
                                    <label>RECORDARME</label>
                                </div>
                            </div>
                        </form>
              </div>
              <div class="button-container">
                <a class="button form-button back-color-2" href="{{ url('/password/reset') }}">OLVIDE MI PASSWORD</a>
                <button form="login" type="submit" class="button form-button font back-color-1">
                    INGRESAR
                </button>
              </div>
          </div>
        </div>
@endsection
