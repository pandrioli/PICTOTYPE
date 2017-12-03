<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Game extends Model
{
  const IMAGE_COUNT = 24;
  const MODE_TIME = 0;
  const MODE_POINTS = 1;
  const STATE_INVITATION = 0;
  const STATE_WAITING_OPPONENT = 1;
  const STATE_WAITING_PLAYS = 2;
  const STATE_FINISHED = 3;
  const STATE_CANCELLED = 4;
  const STATE_REJECTED = 5;
  const PLAYER_READY = 0;
  const PLAYER_PLAYING = 1;
  const PLAYER_DONE = 2;

  public function phrase() {
    return $this->belongsTo(Phrase::class);
  }
  public function words() {
    return $this->hasMany(Word::class);
  }
  public function players() {
    return $this->belongsToMany(User::class, 'games_users')->withPivot('state', 'time', 'points');
  }
  public function currentPlayer() {
    return $this->players()->find(Auth::user()->id);
  }
  public function opponentPlayer() {
    return $this->players()->where('users.id', '<>', Auth::user()->id)->first();
  }

  public function setPlayerState($state) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['state' => $state]);
  }
  public function setOpponentState($state) {
    $this->players()->updateExistingPivot($this->opponentPlayer()->id, ['state' => $state]);
  }
  public function setPlayerTime($time) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['time' => $time]);
  }
  public function setPlayerPoints($points) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['points' => $points]);
  }


  public function getPlayerState() {
    return $this->currentPlayer()->pivot->state;
  }
  public function getPlayerTime() {
    return $this->currentPlayer()->pivot->time;
  }
  public function getPlayerPoints() {
    return $this->currentPlayer()->pivot->points;
  }

  public function getOpponentState() {
    return $this->opponentPlayer()->pivot->state;
  }
  public function getOpponentTime() {
    return $this->opponentPlayer()->pivot->time;
  }
  public function getOpponentPoints() {
    return $this->opponentPlayer()->pivot->points;
  }

  public function win() {
    return $this->state == Game::STATE_FINISHED && $this->winner_id == $this->currentPlayer()->id;
  }
  public function lose() {
    return $this->state == Game::STATE_FINISHED && $this->winner_id && $this->winner_id != $this->currentPlayer()->id;
  }

  public function modeString() {
    if ($this->mode == Game::MODE_TIME) {
      return "MENOR TIEMPO";
    } else {
      return "MEJOR PUNTAJE";
    }
  }

  static function formatTime($time) {
      $zero_decimals = "";
      $zero_seconds = "";
      $total_seconds = $time/10;
      $floor_seconds = floor($total_seconds);
      $decimals = floor(60 * ($total_seconds - $floor_seconds));
      $seconds = $floor_seconds % 60;
      $minutes = floor($floor_seconds/60);
      if ($decimals<10) $zero_decimals = "0";
      if ($seconds<10) $zero_seconds = "0";
      return $minutes.":".$zero_seconds.$seconds.".".$zero_decimals.$decimals;
  }

}
