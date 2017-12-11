<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

// clase/modelo de partida

class Game extends Model
{
  // constantes
  const IMAGE_COUNT = 24; // cantidad de imagenes a mostrar
  const MODE_TIME = 0; // modo tiempo
  const MODE_POINTS = 1; // modo puntos
  const STATE_INVITATION = 0; // estado invitacion de partida
  const STATE_WAITING_OPPONENT = 1; // estado esperando oponente
  const STATE_WAITING_PLAYS = 2; // estado esperando jugadas
  const STATE_FINISHED = 3; // estado partida finalizada
  const STATE_CANCELLED = 4; // estado partida cancelada
  const STATE_REJECTED = 5; // estado partida rechazada
  const PLAYER_READY = 0; // estado de jugador listo
  const PLAYER_PLAYING = 1; // estado de jugador jugando
  const PLAYER_DONE = 2; // estado de jugador termino de jugar

  // obtener la frase de la partida
  public function phrase() {
    return $this->belongsTo(Phrase::class);
  }
  // obtener las palabras/imagenes de la partida
  public function words() {
    return $this->hasMany(Word::class);
  }
  // obtener los jugadores de la partida
  public function players() {
    return $this->belongsToMany(User::class, 'games_users')->withPivot('state', 'time', 'points', 'letter_average');
  }
  // obtener el usuario jugador actual de la partida
  public function currentPlayer() {
    return $this->players()->find(Auth::user()->id);
  }
  // obtener el usuario oponente
  public function opponentPlayer() {
    return $this->players()->where('users.id', '<>', Auth::user()->id)->first();
  }

  // setear estado del jugador actual
  public function setPlayerState($state) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['state' => $state]);
  }
  // setear estado del oponente
  public function setOpponentState($state) {
    $this->players()->updateExistingPivot($this->opponentPlayer()->id, ['state' => $state]);
  }
  // setear el tiempo que hizo el jugador actual
  public function setPlayerTime($time) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['time' => $time]);
  }
  // setear los puntos que hizo el jugador actual
  public function setPlayerPoints($points) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['points' => $points]);
  }
  // setear el promedio por letra del jugador actual
  public function setPlayerAverage($average) {
    $this->players()->updateExistingPivot(Auth::user()->id, ['letter_average' => $average]);
  }

  // obtener el estado del jugador actual
  public function getPlayerState() {
    return $this->currentPlayer()->pivot->state;
  }
  // obtener el tiempo que hizo el jugador actual
  public function getPlayerTime() {
    return $this->currentPlayer()->pivot->time;
  }
  // obtener los puntos que hizo el jugador actual
  public function getPlayerPoints() {
    return $this->currentPlayer()->pivot->points;
  }
  // obtener el promedio por letra del jugador actual
  public function getPlayerAverage() {
    return $this->currentPlayer()->pivot->letter_average;
  }

  // obtener el estado del oponente
  public function getOpponentState() {
    return $this->opponentPlayer()->pivot->state;
  }
  // obtener el tiempo que hizo el oponente
  public function getOpponentTime() {
    return $this->opponentPlayer()->pivot->time;
  }
  // obtener los puntos que hizo el oponente
  public function getOpponentPoints() {
    return $this->opponentPlayer()->pivot->points;
  }
  // obtener el promedio por letra del oponente
  public function getOpponentAverage() {
    return $this->opponentPlayer()->pivot->letter_average;
  }

  // obtener si la partida la gano el jugador a actual
  public function win() {
    return $this->state == Game::STATE_FINISHED && $this->winner_id == $this->currentPlayer()->id;
  }
  // obtener si la partida la perdio el jugador actual
  public function lose() {
    return $this->state == Game::STATE_FINISHED && $this->winner_id && $this->winner_id != $this->currentPlayer()->id;
  }

  // obtener el string del modo de la partida
  public function modeString() {
    if ($this->mode == Game::MODE_TIME) {
      return "MENOR TIEMPO";
    } else {
      return "MEJOR PUNTAJE";
    }
  }

  // funcion para formatear el tiempo para mostrar en pantalla
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
