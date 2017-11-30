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
Route::get ('/changetheme', 'UserController@changeTheme');

Route::get ('/newgame', 'GameController@newGame')->name('newgame')->middleware('auth');
Route::get ('/joinpublicgame/{mode}', 'GameController@joinPublicGame')->middleware('auth');
Route::get ('/creategame/{pract}', 'GameController@createGameForm');
Route::post('/creategame', 'GameController@createGame');
Route::get ('/viewgame/{id}', 'GameController@viewGame')->name('viewgame')->middleware('auth');

Route::get ('/gameplay/{id}', 'GameController@playGame')->name('gameplay');
Route::post('/finishgame', 'GameController@finishGame');
Route::get ('/cancelgame/{game_id}/{user_id}', 'GameController@cancelGame');

//API
Route::get('/api/availablepublicgames', 'APIController@availablePublicGames');
Route::get('/api/updatedusergames/{timestamp}', 'APIController@updatedUserGames');
Route::get('/api/newnotifications/{timestamp}', 'APIController@newNotifications');
Route::get('/api/notificationsread', 'APIController@notificationsRead');
