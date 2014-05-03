<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

/**
 * Handles all game actions
 *
 */
class GameController extends BaseController {
	
	public function showHomeScreen() {
		$user = Auth::user();
		$score = $user->getScore();
		$games_won = $user->getGamesWon();
		$games_lost = $user->getGamesLost();
		$games_undecided = $user->getGamesUndecided();
		$games_played = $user->getGamesPlayed();
		$pending_games = array();
		// get all pending games where current user is player 1
		$games_as_pl1 = Game::where('player1_id', '=', $user->getId())->whereNull('finished_on')->get();
		
		foreach($games_as_pl1 as $game) {
			$link = 'game/player1/'.$game->getId();
			$opponent = $game->getOpponent($user);
			$start_date = $game->created_at;
			$entry = array(
					'link' => $link, 
					'opponent' => $opponent->getEmail(),
					'start_date' => $start_date
			);
			array_push($pending_games, $entry);
		}
		// get all pending games where current user is player 2
		$games_as_pl2 = Game::where('player2_id', '=', $user->getId())->whereNull('finished_on')->get();
		
		foreach($games_as_pl2 as $game) {
			$link = 'game/player2/'.$game->getId();
			$opponent = $game->getOpponent($user);
			$start_date = $game->created_at;
			$entry = array(
					'link' => $link, 
					'opponent' => $opponent->getEmail(),
					'start_date' => $start_date
			);
			
			array_push($pending_games, $entry);
		}
		
		return View::make('home')
			->with('username', Auth::user()->getName())
			->with('games_won', $games_won)
			->with('games_lost', $games_lost)
			->with('games_undecided', $games_undecided)
			->with('games_played', $games_played)
			->with('score', $score)
			->with('pending_games', $pending_games);
	}
	
	/**
	 * Get request on 'game/new'
	 * Shows the form for starting a new game
	 */
	public function getNewGame() {
		return View::make('newgame')->with('game', null);
	}
	/**
	 * Handles the post request on 'game/new'
	 * Validates the input and creates a new game with given parameters
	 */
	public function postNewGame() {
		// define the validation rules for the given input
		$rules = array('email' => 'required|email');
		// create a validator instance
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails())
		{
			// redirect to the input form, populate form with
			// input data and display validation errors
			return Redirect::to('game/new')->withInput()->withErrors($validator);
				
		} else {
			$email = Input::get('email');
			$message_text = Input::get('messagetext');
			$player1 = Auth::user();
			$player2 = $this->getOrCreateUser($email);
			
			$game = $this->startGame($player1, $player2);
			
			// send an email invitation to player 2
			App::make('mail_service')->sendInvitation($email, $game->getId(), $message_text);
			
			return Redirect::to('game/player1/'.$game->getId());
		}
	}
	
	/**
	 * Starts a new game for given players
	 * 
	 * @param User $player1
	 * @param User $player2
	 */
	private function startGame($player1, $player2)
	{
		// As this task spans several database inserts, we execute this
		// as a transaction
		DB::beginTransaction();
		try {
			$qst_amount = 5;
			
			$game = new Game();
			// create a unique id for our new game
			$id = md5(uniqid());
			$game->setId($id);
			
			$game->setPlayer1($player1);
			$game->setPlayer2($player2);
			
			$game->save();
			
			// create a set of random questions for the game
			$questions = Question::all()->random($qst_amount);
			$game->setQuestions($questions);

			DB::commit();
			
			return $game;
				
		} catch (PDOException $e) {
			DB::rollback();
			throw $e;
		}
	
	}
	
	/**
	 * Populates and displays the game view for given player.
	 * Checks first, if given player has already played and redirects 
	 * to result page in that case.
	 * 
	 * @param $player The player index (can be either 1 or 2)
	 * @param $game	The game instance
	 */
	public function getHandlePlayer($player_index, $game) {
		// redirect to result page if no question left for given player
		$gq = $game->nextQuestionFor($player_index);

		if($gq) {
			// populate and display our game view with the current question
			$question = $gq->getQuestion()->getQuestionText();
			$answers = $gq->getQuestion()->getAnswers();
			// don't forget to shuffle the answers because the first answer
			// is always the right one
			shuffle($answers);
			// set the current question as displayed
			$gq->setDisplayed();
			// assign all parameters to our view and display it
			return View::make('game', array(
					'game_question_id' => $gq->getId(),
					'player' => Auth::user()->getName(),
					'question' => $question,
					'answers' => $answers
			));
		} else {
			return Redirect::to('game/'.$game->getId().'/result');
		}
	}
	
	/**
	 * @param GameQuestion $game_question
	 */
	public function postHandleAnswer($game_question) {
		$answer = Input::get('submit');
		$game_question->setAnswer($answer);
		$url = 'game/player'.$game_question->getPlayerIndex().'/'.$game_question->getGame()->getId();
		
		return Redirect::to($url);
	}
	
	/**
	 * Displays the results of given game.
	 */
	public function showResult($game) {
		// if the game is not finished yet, we just show
		// a message
		if(!$game->isFinished()) {
			return View::make('result')
				->with('isFinished', false)
				->with('opponent', $game->getOpponent(Auth::user())->getName());
			
		// otherwise we display the results
		} else {
			$pl1_name = $game->getPlayer1()->getEmail();
			$pl1_score = $game->getPlayer1Score();
			$pl2_name = $game->getPlayer2()->getEmail();
			$pl2_score = $game->getPlayer2Score();
			$pl1_results = $game->getResults(1);
			$pl2_results = $game->getResults(2);
			
			return View::make('result')
				->with('isFinished', true)
				->with('player1_name', $pl1_name)
				->with('player2_name', $pl2_name)
				->with('player1_score', $pl1_score)
				->with('player2_score', $pl2_score)
				->with('player1_results', $pl1_results)
				->with('player2_results', $pl2_results)
				->with('game_id', $game->getId());
		}
		
	}
	
	public function handleRevenche($game) {
		$player1 = Auth::user();
		$player2 = $game->getOpponent($player1);
		
		$new_game = $this->startGame($player1, $player2);
		$email = $player2->getEmail();
		$id = $new_game->getId();
		$message_text = "I want a revenche!";
		// send an email invitation to player 2
		App::make('mail_service')->sendInvitation($email, $id, $message_text);
		
		return Redirect::to('game/player1/'.$id);
	}
	
	// ------------------------------- Private members --------------------------------------------//
	
	/**
	 * Returns the user with given email. If it does not already exist, a new user
	 * with given name and email will be created.
	 *
	 * @param unknown $email
	 * @param unknown $name
	 * @return User
	 */
	private function getOrCreateUser($email) {
		$user = User::where('email', '=', $email)->first();
		// create a new user, if no user with given email was found
		if(!$user) {
			$user = new User();
			$user->setEmail($email);
			$user->setName('');
			$user->save();
		}
	
		return $user;
	}

}
