@extends('base_site')

@section('title', 'PICTOTYPE')
@section('header-button', 'home')

@section('content')
  @php
    $files = glob(public_path('img/pictotypes/*.*'));
    shuffle($files);
  @endphp
  <div class="center-screen">
      <div class="window-container w700">
        <div class="window-header back-color-2">
          PANEL DE ADMINISTRADOR
        </div>
        <div class="tab-container">
          <input id="tab1" type="radio" name="tabs" value="1" hidden checked onchange="switchPanel(0)">
          <label for="tab1" class="tab back-color-1">
              FRASES
            </label>
            <input id="tab2" type="radio" name="tabs" value="2" hidden onchange="switchPanel(1)">
            <label for="tab2" class="tab back-color-1">
              PARTIDAS INACTIVAS
            </label>
        </div>
        <div class="switch-container back-color-1" style="border-bottom-left-radius:10px;">
          <form id="frases" class="switch-panel" style="opacity:1; pointer-events:all;" method="post">
            <div id="phrasesContainer" class="item-list">
                {{ csrf_field() }}
                @foreach ($phrases->reverse() as $key => $phrase)
                <input form="frases" name="{{$phrase->id}}" class="game-item font uppercase" type="text" value="{{$phrase->phrase}}" style="width:100%; font-weight:bold;" disabled>
                @endforeach
                <input id="phrasesCount" type="text" name="cantidad" value="{{$phrases->count()}}" hidden>
            </div>
          </form>
          <div class="switch-panel">
            <div class="item-list">
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
                <label class="game-item font uppercase">Partida Inactiva Ex</label>
            </div>
          </div>
        </div>
        <div class="button-container" style="" id="panel0">
          <button type="button" class="form-button button back-color-2 font" id="btnEdit">EDITAR</button>
          <button type="button" class="form-button button back-color-2 font" onclick="addPhrase()">AGREGAR</button>
          <button type="submit" class="form-button button back-color-2 font" >GUARDAR</button>
        </div>
        <div class="button-container" style="display:none;" id="panel1">
          <button type="button" class="form-button button back-color-2 font" id="btnBorrar">BORRAR PARTIDAS</button>
        </div>
      </div>
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

    function switchPanel(index) {
      setCookie('active-home-tab', index);
      var panels = document.getElementsByClassName('switch-panel');
      var btnPanels = [document.getElementById('panel0'), document.getElementById('panel1')];
      for (var i=0; i< panels.length; i++) {
        panels[i].style.opacity="0";
        panels[i].style.pointerEvents="none";
      }
      for (var i = 0; i < btnPanels.length; i++) {
        btnPanels[i].style.display = "none";
      }
      panels[index].style.opacity="1";
      panels[index].style.pointerEvents="all";
      btnPanels[index].style.display = "";

    }
  </script>
@endsection
