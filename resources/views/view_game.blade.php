@extends('base_site')

@php
  use App\Game;
  $player = $game->currentPlayer();
  $player_state = $game->getPlayerState();
  $opponent = $game->opponentPlayer();
  if ($opponent) $opponent_state = $game->getOpponentState();
  $tied = !$game->winner_id && $game->state == Game::STATE_FINISHED;
  $winner = !$tied && $game->winner_id == $player->id && $game->state == Game::STATE_FINISHED;
  $loser = !$tied && $game->winner_id != $player->id && $game->state == Game::STATE_FINISHED;
@endphp

@section('content')
  <style>
    .opponent-avatar {
      @if ($opponent)
        background: url({{url($opponent->avatar)}});
      @else
        background: url({{url('img/profile.png')}});
      @endif
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>
  <div class="center-screen">
    <div class="game-results-container">
      <div class="form-heading back-color-2">
        RESULTADOS - MODO {{ $game->modeString() }}
      </div>
      <div class="user-results-panel back-color-1 {{ $winner?'winner':''}} {{ $loser?'loser':''}}">
          <div class="user-results-avatar avatar"></div>
          <div class="user-result-name">
            {{ $player->username }}
          </div>
          <div class="user-result">
            @if ($player_state == GAME::PLAYER_DONE)
              @if ($game->mode==0)
                TIEMPO: <span>{{ Game::formatTime($game->getPlayerTime()) }}</span>
              @else
                PUNTOS: <span>{{ $game->getPlayerPoints() }}</span>
              @endif
            @else
              PENDIENTE JUGAR
            @endif
          </div>
      </div>
      <div class="user-results-panel back-color-3 {{ $winner?'loser':'' }} {{ $loser?'winner':'' }}">
          <div class="user-results-avatar opponent-avatar"></div>
          @if ($opponent)
            <div class="user-result-name">
              {{ $opponent->username }}
            </div>
            <div class="user-result">
              @if ($opponent_state == Game::PLAYER_DONE)
                @if ($game->mode==0)
                  TIEMPO: <span>{{ Game::formatTime($game->getOpponentTime()) }}</span>
                @else
                  PUNTOS: <span>{{ $game->getOpponentPoints() }}</span>
                @endif
              @else
                PENDIENTE JUGAR
              @endif
            </div>
          @else
            <div class="user-result-name">
              esperando oponente
            </div>
          @endif
      </div>
      <div class="form-button-container">
      @if ($player_state == GAME::PLAYER_DONE)
        <a class="button form-button back-color-1" href="/">VOLVER A PANTALLA PRINCIPAL</a>
      @else
        <a class="button form-button back-color-2" href="/">VOLVER A PANTALLA PRINCIPAL</a>
        <a class="button form-button back-color-1" href="/gameplay/{{ $game->id }}">JUGAR</a>
      @endif
      </div>
    </div>
  </div>
@endsection
