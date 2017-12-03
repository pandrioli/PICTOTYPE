@extends('base_site')

@php
  use App\Game;
  if ($game->state != Game::STATE_INVITATION) {
    $player = $game->currentPlayer();
    $player_state = $game->getPlayerState();
    $tied = $game->winner_id === 0;
    $winner = !$tied && $game->winner_id == $player->id && $game->state == Game::STATE_FINISHED;
    $loser = !$tied && $game->winner_id != $player->id && $game->state == Game::STATE_FINISHED;
  } else {
    $player_state = 0;
    $tied = 0;
    $winner = 0;
    $loser = 0;
  }
  $opponent = $game->opponentPlayer();
  if ($opponent) $opponent_state = $game->getOpponentState();
@endphp

@section('content')
  <style>
    .opponent-avatar {
      @if ($opponent)
        background: url({{$opponent->avatar}});
      @else
        background: url('/img/profile.png');
      @endif
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
    }
  </style>
  <div class="center-screen">
    <div class="window-container w700">
      <div class="window-header back-color-2">
        {{$game->state == Game::STATE_INVITATION ? 'INVITACION':'RESULTADOS'}} - MODO {{ $game->modeString() }}
      </div>
      @if ($game->state == Game::STATE_CANCELLED)
        <div class="user-results-panel back-color-3 winner center-text">
          <h1>PARTIDA CANCELADA</h1>
        </div>
      @else
        @if ($game->state != Game::STATE_INVITATION)
          <div class="user-results-panel back-color-1 {{ $winner||$tied?'winner':''}} {{ $loser?'loser':''}}">
              <div class="user-results-avatar avatar"></div>
              <div class="user-result-name">
                {{ $player->username }}
              </div>
              <div class="user-result">
                @if ($tied && $player_state != Game::PLAYER_DONE)
                  (DESEMPATE PENDIENTE)
                @endif
                @if ($player_state == GAME::PLAYER_DONE || $tied)
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
        @endif
        <div class="user-results-panel back-color-3 {{ $winner?'loser':'' }} {{ $loser||$tied?'winner':'' }}">
            <div class="user-results-avatar opponent-avatar"></div>
            @if ($opponent)
              <div class="user-result-name">
                {{ $opponent->username }}
              </div>
              <div class="user-result">
                @if ($tied && $opponent_state != Game::PLAYER_DONE)
                  (DESEMPATE PENDIENTE)
                @endif
                @if ($opponent_state == Game::PLAYER_DONE || $tied)
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
      @endif
      <div class="button-container">
        @if ($game->state == Game::STATE_INVITATION)
          <a class="button form-button back-color-2" href="/game/reject/{{ $game->id }}/{{ $opponent->id }}">RECHAZAR</a>
          <a class="button form-button back-color-1" href="/game/accept/{{ $game->id }}/{{ $opponent->id }}">ACEPTAR</a>
        @else
          <a class="button form-button {{$player_state == GAME::PLAYER_DONE ?'back-color-1':'back-color-2'}}" href="/">HOME</a>
          @if ($opponent)
            <a class="button form-button back-color-2" href="/user/view/{{ $opponent->id }}/game-{{ $game->id }}">VER OPONENTE</a>
          @endif
          @if ($player_state != GAME::PLAYER_DONE)
            <a class="button form-button back-color-1" href="/game/play/{{ $game->id }}">
              @if ($tied)
                DESEMPATAR
              @else
                JUGAR
              @endif
            </a>
          @endif
        </div>
      @endif
    </div>
  </div>
@endsection
