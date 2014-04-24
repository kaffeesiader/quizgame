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
 * Assign model bindings
 */
Route::model('game', 'Game');



Route::get('/', function()
{
	return View::make('hello');
});

Route::get('game/new', array('as' => 'game.new', 'uses' => 'GameController@newGame'));

Route::post('game/new', array('as' => 'game.start', 'uses' => 'GameController@startGame'));

Route::get('game/player{pid}/{game}', 'GameController@handlePlayer');

Route::post('game/player{pid}/{game}', array('as' => 'game.play', 'uses' => 'GameController@handlePlayerMove'));

Route::get('game/{id}/result', 'GameController@showResult');