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

Route::get ('/game/public', 'GameController@publicGame')->name('publicgame')->middleware('auth');
Route::get ('/game/joinpublic/{mode}', 'GameController@joinPublicGame')->middleware('auth');
Route::get ('/game/create/{data}', 'GameController@createGameForm');
Route::post('/game/create', 'GameController@createGame');
Route::get ('/game/view/{id}', 'GameController@viewGame')->name('viewgame')->middleware('auth');
Route::get ('/game/accept/{game_id}/{user_id}', 'GameController@acceptGame')->middleware('auth');
Route::get ('/game/reject/{game_id}/{user_id}', 'GameController@rejectGame')->middleware('auth');
Route::get ('/game/play/{id}', 'GameController@playGame')->name('gameplay');
Route::post('/game/finish', 'GameController@finishGame');
Route::get ('/game/cancel/{game_id}/{user_id}', 'GameController@cancelGame');

Route::get('/user/friends', 'UserController@friendsPage')->middleware('auth');
Route::get('/user/friends/request/{id}/{back}', 'UserController@friendshipRequest')->middleware('auth');
Route::get('/user/friends/accept/{id}/{back}', 'UserController@friendshipAccept')->middleware('auth');
Route::get('/user/friends/cancel/{id}/{back}', 'UserController@friendshipCancel')->middleware('auth');
Route::get('/user/view/{id}/{back}', 'UserController@viewUser')->name('viewuser')->middleware('auth');
Route::get('/user/search/{query}', 'UserController@searchUsers')->middleware('auth');


//API
Route::get('/api/games/availablepublic', 'APIController@availablePublicGames');
Route::get('/api/games/updated/{timestamp}', 'APIController@updatedUserGames');
Route::get('/api/notifications/new/{timestamp}', 'APIController@newNotifications');
Route::get('/api/notifications/setread/{id}', 'APIController@notificationRead');
Route::get('/api/notifications/setallreadandget', 'APIController@notificationsAllRead');
