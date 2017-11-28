@php
  $avatar = "";
  if (\Auth::check()) {
    $avatar = \Auth::user()->avatar;
  }
@endphp

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/site_styles.css">
    <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/pretty-checkbox.min.css">
    <title>@yield('title')</title>
    <script>
        var image_path = '{{ asset('img') }}';
        window.addEventListener('load', activateHeaderButton);
        function activateHeaderButton() {
          var header_button = document.getElementById('header-@yield('header-button')');
          if (header_button) header_button.style.opacity = 1;
        }
    </script>
    <script type="text/javascript" src="@yield('script')"></script>
    <script type="text/javascript" src="{{asset('js/functions.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/background.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/TweenLite.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/CSSPlugin.min.js')}}"></script>
    <style>
      .avatar {
        background: url('{{url($avatar)}}');
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
      }
    </style>
  </head>
  <body class="font">
    <div class="site-container">
      @include('header')
      @yield('content')
    </div>
  </body>
</html>
