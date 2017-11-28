@extends('base_site')

@section('title', 'PICTOTYPE')
@section('script', asset('js/home.js'))
@section('header-button', 'home')

@section('content')
  @php
    $files = glob(public_path('img/pictotypes/*.*'));
    shuffle($files);
  @endphp
  <script type="text/javascript">
    var filenames = new Array();
    @foreach ($files as $n=>$file)
      filenames[{{ $n }}] = '{{ pathinfo($file)['basename'] }}';
    @endforeach
  </script>
  <div class="center-screen">
    <div class="home-image-container">
      <img id="image-0"></img>
      <img id="image-1"></img>
    </div>
  </div>
  <div class="home-image-word"><div id="image-word"></div></div>
@endsection
