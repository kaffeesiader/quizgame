<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

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
Route::pattern('number', '[0-9]+');

/*
 * Define route filters
 */


/*
 * Assign model bindings
 */

// Route::when('game/*', 'auth');

/*
 * Route to home screen
 */
Route::get('/', function() {
	// return our empty template view
	return View::make('layout');
});

Route::post('game/new', array('as' => 'game.start', 'uses' => 'GameController@postNewGame'));

Route::get('game/{game_id}', 'GameController@getGame');

Route::post('game/player/{player_id}/{game_id}', 'GameController@postGame');

Route::get('game/{game_id}/result', 'GameController@getResults');

Route::get('game/{game_id}/revenche', 'GameController@handleRevenche');

// Route::get('game/highscore', 'GameController@getHighscore');

// Route::get('user/statistics', 'GameController@getUserStats');

// Route::get('user/opengames', 'GameController@getPendingGames');

// route to process the login form
// Route::post('login', array('uses' => 'LoginController@doLogin'));

// Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@logout'));

Route::post('test', function() {
		if(!Input::isJson()) {
			echo 'not json';
		} else {
			echo 'json';
		}
 		print_r(Input::all());
 		
		$request = Request::instance();
		$content = $request->getContent();
});
