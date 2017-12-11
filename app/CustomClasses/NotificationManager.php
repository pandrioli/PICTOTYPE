<?php

namespace App\CustomClasses;

use Illuminate\Support\Facades\Auth;
use App\Game;
use App\Notification;

// clase creada para enviar NOTIFICACIONES
// es inyectada en el constructor de GameController y UserController
// mediante NotificationsServiceProvider

class NotificationManager {

  // notificacion de que un amigo te agrego a una partida
  public function notifyGameCreated($game_id, $user_id) {
    $message = "<span>".Auth::user()->username."</span> TE AGREGO A UNA PARTIDA";
    $this->notifyGame($game_id, $user_id, $message, true);
  }
  // notificacion de invitacion a una partida
  public function notifyGameInvitation($game_id, $user_id) {
    $message = "<span>".Auth::user()->username."</span> TE INVITO A UNA PARTIDA";
    $this->notifyGame($game_id, $user_id, $message, true);
  }
  // notificacion de invitacion aceptada
  public function notifyGameAccepted($game_id, $user_id) {
    $message = "<span>".Auth::user()->username."</span> ACEPTO TU INVITACION";
    $this->notifyGame($game_id, $user_id, $message, true);
  }
  // notificacion de invitacion rechazada
  public function notifyGameRejected($game_id, $user_id) {
    $message = "<span>".Auth::user()->username."</span> RECHAZO TU INVITACION";
    $this->notifyGame($game_id, $user_id, $message, false);
  }

  // notificacion de fin de partida o empate
  public function notifyGameFinished(Game $game) {
    $user1 = $game->currentPlayer();
    $user2 = $game->opponentPlayer();
    // se define quien es el ganador y el perdedor, en caso de no haber empate.
    if ($game->winner_id == $user1->id) {
      $winner = $user1;
      $loser = $user2;
    }
    if ($game->winner_id == $user2->id) {
      $winner = $user2;
      $loser = $user1;
    }
    if (!$game->winner_id) { // empate, se notifica a los dos jugadores
      $message = "<span>EMPATASTE</span> UNA PARTIDA CON <span>".$user2->username."</span>";
      $this->notifyGame($game->id, $user1->id, $message, true);
      $message = "<span>EMPATASTE</span> UNA PARTIDA CON <span>".$user1->username."</span>";
      $this->notifyGame($game->id, $user2->id, $message, true);
    } else { // hay un ganador, se notifica al ganador y al perdedor
      $message = "<span>GANASTE</span> UNA PARTIDA CON <span>".$loser->username."</span>";
      $this->notifyGame($game->id, $winner->id, $message, true);
      $message = "<span>PERDISTE</span> UNA PARTIDA CON <span>".$winner->username."</span>";
      $this->notifyGame($game->id, $loser->id, $message, true);
    }
  }

  // notificacion de juego cancelado
  public function notifyGameCancelled(Game $game) {
    $message = "<span>".Auth::user()->username."</span> CANCELO UNA PARTIDA";
    $user_id = $game->opponentPlayer()->id;
    $this->notifyGame($game->id, $user_id, $message, true);
  }

  // notificacion de pedido de amistad
  public function notifyFriendshipRequest($user_id) {
    $message = "<span>".Auth::user()->username."</span> QUIERE SER TU AMIGO";
    $this->notifyFriendship($user_id, $message);
  }

  // notificacion de amistad aceptada
  public function notifyFriendshipAccepted($user_id) {
    $message = "<span>".Auth::user()->username."</span> ACEPTO TU AMISTAD";
    $this->notifyFriendship($user_id, $message);
  }

  // notificacion de amistad cancelada
  public function notifyFriendshipCancelled($user_id) {
    $message = "<span>".Auth::user()->username."</span> CANCELO TU AMISTAD";
    $this->notifyFriendship($user_id, $message);
  }

  // funcion privada para notificar acerca de partidas
  private function notifyGame($game_id, $user_id, $message, $clickeable) {
    $notification = new Notification();
    $notification->type = Notification::NOTIFY_GAME;
    $notification->game_id = $game_id;
    $notification->user_id = $user_id;
    $notification->message = $message;
    $notification->clickeable = $clickeable;
    $notification->save();
  }

  // funcion privada para notificar acerca de amistad de usuarios
  private function notifyFriendship($user_id, $message) {
    $notification = new Notification();
    $notification->type = Notification::NOTIFY_FRIENDSHIP;
    $notification->user_id = $user_id;
    $notification->sender_id = Auth::user()->id;
    $notification->message = $message;
    $notification->clickeable = true;
    $notification->save();
  }
}
