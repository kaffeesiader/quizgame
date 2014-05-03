<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
 * Define route constraint patterns
 */
Route::pattern('pid', '[1|2]');

/*
 * Define route filters
 */
Route::filter('check_username', function()
{
	// make sure that user has set his name, otherwise redirect
	$username = Auth::user()->getName();
	if (empty($username))
	{
		return Redirect::to('user/new');
	}
});

/*
 * Assign model bindings
 */
Route::model('game', 'Game');
Route::model('game_question_id', 'GameQuestion');

Route::when('/*', 'auth|check_username');

/*
 * Route to home screen
 */
Route::get('/', 'GameController@showHomeScreen');

Route::get('game/new', array('as' => 'game.new', 'uses' => 'GameController@getNewGame'));

Route::post('game/new', array('as' => 'game.start', 'uses' => 'GameController@postNewGame'));

Route::get('game/player{pid}/{game}', 'GameController@getHandlePlayer');

Route::post('game/answer/{game_question_id}', 'GameController@postHandleAnswer');

Route::get('game/{game}/result', 'GameController@showResult');

Route::get('game/{game}/revenche', 'GameController@handleRevenche');

Route::get('login', array('uses' => 'LoginController@showLogin'));
// route to process the login form
Route::post('login', array('uses' => 'LoginController@doLogin'));

Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));

Route::get('user/new', array('uses' => 'LoginController@showCreateUser'));

Route::post('user/new', array('uses' => 'LoginController@createUser'));