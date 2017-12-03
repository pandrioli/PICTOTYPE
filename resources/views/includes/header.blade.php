@php
  use Illuminate\Support\Facades\Auth;
  $user = Auth::user();
  $logged = Auth::check();
@endphp

<header class='site-header'>
  <input type='checkbox' id='toggle-checkbox' hidden>
  <a href='{{ $logged ? '/changetheme' : '/' }}' class='button logo'>
    <span class="color-1">PICTO</span><span class='color-2'>TYPE</span>
  </a>
  <label for="toggle-checkbox" class='header-item button back-color-1' id='toggle-menu'><i class="fa fa-bars" aria-hidden="true"></i></label>
  <div class='header-menu'>
    @if ($logged)
      <a class='header-item button back-color-1' href='/logout'>SALIR</a>
      <a id="header-profile" class='header-item button back-color-1' href='/profile'><div class="header-avatar avatar"></div>PERFIL</a>
    @else
      <a id="header-login" class='header-item button back-color-1' href='/login'>INGRESAR</a>
      <a id="header-register" class='header-item button back-color-1' href='/register'>REGISTRARSE</a>
    @endif
    <a id="header-practique" class='header-item button back-color-2' href='/game/create/practique'>PRACTICAR </a>
    <a class='header-item button back-color-2' href=''>TUTORIAL</a>
    @if ($logged)
      <a class='header-item button back-color-1' href=''>RANKING</a>
    @endif
    <a id="header-home" class='header-item button back-color-1' href='/'>HOME</a>
  </div>
</header>
