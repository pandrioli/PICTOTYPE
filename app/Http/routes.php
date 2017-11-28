<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();
Route::get ('/', 'HomeController@home')->name('home');
Route::get ('/profile', 'UserController@profile')->name('profile')->middleware('auth');
Route::post('/profile', 'UserController@update')->middleware('auth');

Route::get ('/newgame', 'GameController@newGame')->name('newgame')->middleware('auth');
Route::get ('/joinpublicgame/{mode}', 'GameController@joinPublicGame')->middleware('auth');
Route::get ('/creategame/{pract}', 'GameController@createGameForm');
Route::post('/creategame', 'GameController@createGame');
Route::get ('/viewgame/{id}', 'GameController@viewGame')->name('viewgame')->middleware('auth');

Route::get ('/gameplay/{id}', 'GameController@playGame')->name('gameplay')->middleware('auth');
Route::post('/finishgame', 'GameController@finishGame');
Route::get ('/cancelgame/{game_id}/{user_id}', 'GameController@cancelGame');

Route::get('/api/availablepublicgames', 'APIController@availablePublicGames');
Route::get('/api/updatedusergames/{timestamp}', 'APIController@updatedUserGames');
