<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Game;
use App\Notification;

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

  public function newNotifications($timestamp) {
    $notifications = Auth::user()->notifications->filter(function($notif) use ($timestamp) {
      return $notif->created_at > $timestamp;
    });
    if ($notifications->count() > 0) {
      $html = view('includes/notification_items',compact('notifications'));
      return json_encode(['timestamp' => $notifications->values()->first()->created_at->format('Y-m-d H:i:s'), 'html' => (string)$html]);
    } else {
      return "{}";
    }
  }

  public function notificationRead($id) {
    $notification = Notification::find($id);
    $notification->read = 1;
    $notification->save();
    return "{}";
  }

  public function notificationsAllRead() {
    $notifications = Auth::user()->notifications->where('read', 0)->all();
    foreach ($notifications as $notification) {
      $notification->read = 1;
      $notification->save();
    }
    $notifications = Auth::user()->notifications;
    if ($notifications->count() > 0) {
      $html = view('includes/notification_items',compact('notifications'));
      return json_encode(['timestamp' => $notifications->values()->first()->created_at->format('Y-m-d H:i:s'), 'html' => (string)$html]);
    } else {
      return "{'timestamp' => '','html' => ''}";
    }
  }
}
