<?php

namespace App\CustomClasses;

use Illuminate\Support\Facades\Auth;
use App\Game;
use App\Notification;

class NotificationManager {

  public function notifyGameFinished(Game $game) {
    $user1 = $game->currentPlayer();
    $user2 = $game->opponentPlayer();
    if ($game->winner_id == $user1->id) {
      $winner = $user1;
      $loser = $user2;
    }
    if ($game->winner_id == $user2->id) {
      $winner = $user2;
      $loser = $user1;
    }
    if (!$game->winner_id) {
      $message = "</span>EMPATASTE</span> UNA PARTIDA CON <span>".$user2->username."</span>";
      $this->notifyGame($game->id, $user1->id, $message, true);
      $message = "<span>EMPATASTE</span> UNA PARTIDA CON <span>".$user1->username."</span>";
      $this->notifyGame($game->id, $user2->id, $message, true);
    } else {
      $message = "<span>GANASTE</span> UNA PARTIDA CON <span>".$loser->username."</span>";
      $this->notifyGame($game->id, $winner->id, $message, true);
      $message = "<span>PERDISTE</span> UNA PARTIDA CON <span>".$winner->username."</span>";
      $this->notifyGame($game->id, $loser->id, $message, true);
    }
  }

  public function notifyGameCancelled(Game $game) {
    $message = "<span>".Auth::user()->username."</span> CANCELO UNA PARTIDA";
    $user_id = $game->opponentPlayer()->id;
    $this->notifyGame($game->id, $user_id, $message, false);
  }

  private function notifyGame($game_id, $user_id, $message, $clickeable) {
    $notification = new Notification();
    $notification->type = Notification::NOTIFY_GAME;
    $notification->game_id = $game_id;
    $notification->user_id = $user_id;
    $notification->message = $message;
    $notification->clickeable = $clickeable;
    $notification->save();
  }
}
