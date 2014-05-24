<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Handles all game actions
 *
 */
class GameController extends BaseController {
	
	/**
	 * Handles the post request on 'game/new'
	 * Validates the input and creates a new game with given parameters
	 */
	public function postNewGame() {
		// define the validation rules for the given input
		$rules = array(
				'email1' => 'required|email|different:email2',
				'name1' => 'required|min:4',
				'email2' => 'required|email|different:email1',
				'name2' => 'required|min:4'
		);
		// create a validator instance
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails())
		{
			Log::error("Game start failed due to data validation error!");
			Log::info($validator->messages()->toJson());
			// redirect to the input form, populate form with
			// input data and display validation errors
			return Response::json($validator->messages()->toArray(), 500);
				
		} else {
			$email1 = Input::get('email1');
			$name1 = Input::get('name1');
			$email2 = Input::get('email2');
			$name2 = Input::get('name2');
			
			$player_1 = array('name' => $name1, 'email' => $email1);
			$player_2 = array('name' => $name2, 'email' => $email2);
			$players = array($player_1, $player_2);
			
			$message_text = Input::get('messagetext');
			$n_qst = 5;
			$game_id = Game::start($players, $n_qst);
			
			// send an email invitation to player 2
			App::make('mail_service')->sendInvitation($email2, $game_id, $message_text);
			Log::info("New game started with id ".$game_id);
			
			return Response::json(array('game_id' => $game_id));
		}
	}
	
	/**
	 * Return the game with given id
	 * 
	 * @param $game_id	The id of the game
	 */
	public function getGame($game_id) {
		
		$game = Game::get($game_id);
		if(!empty($game)) {
			return Response::json($game);
		} else {
			Log::error("GameController: Unable to handle player!");
			return Response::make("No game with given ID exists", 404);
		}
	}
	
	/**
	 * Handle the move of player with given index on game with given id
	 * 
	 * @param string game_id
	 */
	public function postGame($player_index, $game_id) {
		if(Input::isJson()) {
			$data = Input::all();
			Game::update($game_id, $player_index, $data);

			return Response::make("Ok", 200);
		} else {
			Log::error("Input has to be in json format!");
		}
	}
	/**
	 * Get the results of the game with given ID
	 * @param string $game_id
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
	 */
	public function getResults($game_id) {
		$results = Game::get($game_id);
		if(!empty($results)) {
			return Response::json($results);
		} else {
			Log::error("GameController: Unable to retrieve results - no such game!");
			return Response::make("No game with given ID exists", 404);
		}
	}
	/**
	 * Handles a revenche request of game with given id
	 * Creates a new game and sends the notification emails
	 * 
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
	 */
	public function handleRevenche($game_id) {
		$game = Game::get($game_id);
		if(!empty($game)) {
			$email1 = $game['player1']['player_email'];
			$name1 = $game['player1']['player_name'];
			$email2 = $game['player2']['player_email'];
			$name2 = $game['player2']['player_name'];
			
			$player_1 = array('name' => $name1, 'email' => $email1);
			$player_2 = array('name' => $name2, 'email' => $email2);
			$players = array($player_1, $player_2);
			
			$message = "I want a revenche!";
			$n_qst = 5;
			$game_id = Game::start($players, $n_qst);
			
			// send an email invitation to player 2
			App::make('mail_service')->sendInvitation($email2, $game_id, $message);
			Log::info("New game started with id ".$game_id);
			
			return Response::json(array('game_id' => $game_id));
			
		} else {
			Log::error("GameController: Unable to handle revenche!");
			return Response::make("No game with given ID exists", 404);
		}
	}
	/**
	 * Returns an array of players with the top scores
	 * GET parameter 'count' specifies, how many entries to receive
	 * Default is set to 5
	 * 
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getHighscores() {
		$count = Input::get('count', 5);
		$scores = Game::getHighscores($count);
		return Response::json($scores);
	}

	// ------------------------------- Private members --------------------------------------------//
}
