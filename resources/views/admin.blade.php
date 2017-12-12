@extends('base_site')

@section('title', 'PICTOTYPE')
@section('header-button', 'home')

@section('content')
  @php
    $files = glob(public_path('img/pictotypes/*.*'));
    shuffle($files);
  @endphp
  <div class="center-screen">
    <form id="frases" class="window-container w700" method="post">
      <div class="panel back-color-1" style="border-bottom-left-radius:10px;opacity: 1;">
          <div id="phrasesContainer" class="item-list" style="height:400px">
            {{ csrf_field() }}
            @foreach ($phrases->reverse() as $key => $phrase)
            <input form="frases" name="{{$phrase->id}}" class="game-item font uppercase" type="text" value="{{$phrase->phrase}}" style="width:100%; font-weight:bold;" disabled>
            @endforeach
            <input id="phrasesCount" type="text" name="cantidad" value="{{$phrases->count()}}" hidden>
        </div>
      </div>
      <div class="button-container">
        <button type="button" class="form-button button back-color-2 font" id="btnEdit">EDITAR</button>
        <button type="button" class="form-button button back-color-2 font" onclick="addPhrase()">AGREGAR</button>
        <button type="submit" class="form-button button back-color-2 font" >GUARDAR</button>
      </div>
    </form>
  </div>
  <script type="text/javascript">
    var btnEdit = document.getElementById('btnEdit');


    btnEdit.onclick = enablePhrases;
    function addPhrase(){
      var id = document.querySelectorAll('.game-item').length + 1;
      var container = document.getElementById('phrasesContainer');
      var input = '<input form="frases" name="' + id + '" class="game-item font uppercase" type="text" value="" style="width:100%; font-weight:bold;" disabled>';

      container.innerHTML = input + container.innerHTML;

      document.getElementById('phrasesCount').value = id.toString();
      enablePhrases();
      container.firstElementChild.focus();
    }
    function enablePhrases(){
      var txtPhrases = document.querySelectorAll('.game-item');
      for (var i = 0; i < txtPhrases.length; i++) {
        if (txtPhrases[i].hasAttribute('disabled')) {
          txtPhrases[i].removeAttribute('disabled');
          btnEdit.innerText = "ACEPTAR";
        }
        else {
          txtPhrases[i].setAttribute('disabled','');
          btnEdit.innerText = "EDITAR";
        }
      }
    }
  </script>
@endsection
