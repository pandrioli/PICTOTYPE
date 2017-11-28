<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Game;

use Config;

class APIController extends Controller
{
  public function availablePublicGames() {
    $games0 = DB::table('games')->join('games_users', 'games.id', '=', 'games_users.game_id')
      ->where([['games_users.user_id', '<>', Auth::user()->id], ['games.state', 1], ['games.mode', 0]])
      ->count();
    $games1 = DB::table('games')->join('games_users', 'games.id', '=', 'games_users.game_id')
      ->where([['games_users.user_id', '<>', Auth::user()->id], ['games.state', 1], ['games.mode', 1]])
      ->count();
    return json_encode(['tiempo' => $games0, 'puntaje' => $games1]);
  }

  public function updatedUserGames($timestamp) {
    $updated_games = Auth::user()->games->filter(function($game) use ($timestamp) {
      return $game->updated_at > $timestamp;
    });
    return json_encode($updated_games->values()->all());
  }
}
