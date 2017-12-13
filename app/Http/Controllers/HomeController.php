<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Game;
use App\CustomClasses\NotificationManager;

class HomeController extends Controller
{
    public function __construct(NotificationManager $notifyman) {
      $this->notifyman = $notifyman;
    }
    // controlador que simplemente muestra el home correspondiente a usuario logueado o no
    // y se encarga de cancelar las partidas del usuario que quedaron sin terminar de jugar
    public function home()
    {
        if (!Auth::check()) return view('home'); // no esta logueado, muestra el home con el slide de imagenes
        $user = Auth::user();
        $timestamp = date_create();
        $timestamp = $timestamp->format('Y-m-d H:i:s'); // timestamp que sirve para el tema de las actualizaciones con ajax

        //cancelar partidas que quedaron en modo "jugando"
        foreach ($user->gamesPlaying as $game) {
          $game->state = Game::STATE_CANCELLED;
          $game->setPlayerState(Game::PLAYER_DONE);
          $game->save();
          if ($game->opponentPlayer()) $this->notifyman->notifyGameCancelled($game);
        }

        return view('home_user', compact('user','timestamp')); // esta logueado, muestra el home para usuarios
    }
}
