<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/game_styles.css">
    <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono" rel="stylesheet">
    <title>PictoType</title>
    <script type="text/javascript" src="/js/TweenLite.min.js"></script>
    <script type="text/javascript" src="/js/CSSPlugin.min.js"></script>
    <script type="text/javascript" src="/js/background.js"></script>
    <script type="text/javascript" src="/js/game.js"></script>
  </head>
  <body onload="start()">
    {{ csrf_field() }}
    <div class="loader" id="game-loader">CARGANDO PARTIDA</div>
    <div class="vertical-parent"><div class="vertical-child">
    <section class="game-container" id="game-container" hidden>
      <div id="game-id" hidden>{{ $game->id }}</div>
      <div id="user-id" hidden>{{ $user_id }}</div>
      <div id="game-mode" hidden>{{ $game->mode }}</div>
      <div id="time-per-letter" hidden>{{ $game->time_per_letter }}</div>
      <div id="phrase-text" hidden>{{ $game->phrase->phrase }}</div>
      <div id="image-count" hidden>{{ App\Game::IMAGE_COUNT }}></div>
      <div class="game-panel game-phrase" id="phrase">
        <div id="done-text"></div><div id="current-letter"></div><div id="remaining-text"></div>
      </div>
      <div class="game-panel game-timer" id="timer">
      </div>
      <a class="game-panel game-cancel" href="/cancelgame/{{$game->id}}/{{$user_id}}">
        X
      </a>
      <div class="game-images-container" id="images-container">
        @foreach ($game->words as $game_word)
          <div>
            <img src='/img/pictotypes/{{ $game_word->word }}.jpg' onclick="image_click(this)"  id='word-{{ $game_word->word }}'/>
            <span>{{ str_replace("_","ñ",$game_word->word) }}</span>
          </div>
        @endforeach
      </div>
      <div class="game-popup" id="game-popup"></div>
      <div class="game-popup" id="letter-timer"></div>
    </section>
    </div></div>
  </body>
</html>
