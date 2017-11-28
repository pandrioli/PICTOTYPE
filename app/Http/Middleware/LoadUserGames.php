<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
Use Config;

class LoadUserGames
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
          Config::set('user_games', Auth::user()->games);
        }
        return $next($request);
    }
}
