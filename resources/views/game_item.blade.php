@php
  use App\Game;
  $player = $game->currentPlayer();
  $opponent = $game->opponentPlayer();
@endphp
<style>
  #opponent-avatar-{{ $game->id }} {
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
@if ($key==0)
  <div class="games-list-labels">
    <div>OPONENTE</div>
    <div>MODO</div>
    <div><i class="fa fa-trophy" aria-hidden="true"></i></div>
  </div>
@endif
<a id='game-{{ $game->id }}' class='game-item' href="/viewgame/{{ $game->id }}">
  <div id="opponent-avatar-{{ $game->id }}" class='game-item-avatar'></div>
  <div class='game-item-opponent'>
    @if ($game->state == Game::STATE_CANCELLED)
      JUEGO CANCELADO
    @else
      @if ($opponent)
        {{ $opponent->username }}
      @else
        ESPERANDO OPONENTE
      @endif
    @endif
  </div>
  <div class='game-item-mode'>
    @if ($game->mode == Game::MODE_TIME)
      <i class="fa fa-clock-o" aria-hidden="true"></i>
    @endif
    @if ($game->mode == Game::MODE_POINTS)
      <i class="fa fa-bullseye" aria-hidden="true"></i>
    @endif
  </div>
  <div class='game-item-result'>
  @if ($game->win())
      <i class="fa fa-smile-o" aria-hidden="true" style="color: green;"></i>
  @endif
  @if ($game->lose())
      <i class="fa fa-frown-o" aria-hidden="true" style="color: red; opacity: .4;"></i>
  @endif
</div>
</a>
