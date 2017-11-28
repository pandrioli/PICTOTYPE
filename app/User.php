<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function stats() {
      return $this->hasOne(UserStat::class);
    }

    public function games() {
      return $this->belongsToMany(Game::class, 'games_users')->with('players')->where('practique', 0)->withPivot('state', 'time', 'points');
    }

    public function gamesToPlay() {
      return $this->belongsToMany(Game::class, 'games_users')->where('practique',0)->withPivot('state', 'time', 'points')
        ->where('games_users.state', Game::PLAYER_READY)
        ->orderBy('games.id', 'desc');
    }

    public function gamesPlayed() {
      return $this->belongsToMany(Game::class, 'games_users')->where('practique',0)->withPivot('state', 'time', 'points')
        ->where('games_users.state', Game::PLAYER_DONE)->where('games.state', '<', Game::STATE_FINISHED)
        ->orderBy('games.id', 'desc');
    }

    public function gamesFinished() {
      return $this->belongsToMany(Game::class, 'games_users')->where('practique',0)->withPivot('state', 'time', 'points')
        ->where('games.state', '>=', Game::STATE_FINISHED)
        ->orderBy('games.id', 'desc');
    }

}
