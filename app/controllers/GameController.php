<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

/**
 * Handles all game actions
 *
 */
class GameController extends BaseController {

	/**
	 * Get request on 'game/new'
	 * Shows the form for starting a new game
	 */
	public function newGame() {
		return View::make('newgame')->with('game', null);
	}
	
	/**
	 * Handles the post request on 'game/new'
	 * Validates the input and creates a new game with given parameters
	 */
	public function startGame()
	{
		// define the validation rules for the given input
		$rules = array(
					'player1name' => 'required|min:4|alpha_num',
					'player1email' => 'required|email',
					'player2name' => 'required|min:4|alpha_num',
					'player2email' => 'required|email'
		);
		// create a validator instance
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails())
		{
			// redirect to the input form, populate form with
			// input data and display validation errors
			return Redirect::to('game/new')->withInput()->withErrors($validator);
			
		} else {
			// create a unique id for our new game
			$id = md5(uniqid());
			
			$game = new Game(Input::all());
			$game->id = $id;
			// As this task spans several database inserts, we execute this
			// as a transaction
			DB::beginTransaction();
			try {
				echo "ID: ".$game->id;
				$game->save();
				echo "<br/>ID: ".$game->id;
				
				// create 5 questions randomly and add a GameQuestion object
				// for each one
				foreach (Question::all()->random(5) as $qst) {
					$gq = new GameQuestion();
					$gq->question()->associate($qst);
					$game->gameQuestions()->save($gq);
				}

				DB::commit();
				
				// send an email invitation to player 2
				$to = $game->player2email;
				$subject = 'Quizgame invitation';
				$link = $_SERVER['HTTP_HOST'].'/quizgame/game/player2/'.$id;
				$headers = 'FROM: '.$game->player1email;
				
				$message = "$game->player1name invites you to another round of Quizgame. 
							Please visit $link to play your move";
				
				mail($to, $subject, $message, $headers);
				
				return Redirect::to('game/player1/'.$id);
				
			} catch (PDOException $e) {
				DB::rollback();
				throw $e;
			}
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
	public function handlePlayer($player, $game) {
		// redirect to result page if given player has already played his move
		if($game->hasPlayerPlayed($player)) {
			return Redirect::to('game/'.$game->id.'/result');
		}
		// otherwise populate and display our game view
		$player_name = $player == 1 ? $game->player1name : $game->player2name;
		$questions = array();
		$question_id = 0;
		// put all questions including their possible answers into one array
		foreach($game->gameQuestions as $gq) {
			$question_text = $gq->question->qst_text;
			$answers = array(
					$gq->question->qst_answer1,
					$gq->question->qst_answer2,
					$gq->question->qst_answer3,
					$gq->question->qst_answer4
			);
			// don't forget to shuffle the answers because the first answer
			// is always the right one
			shuffle($answers);
			$question = array(
					'id' => $question_id,
					'text' => $question_text,
					'answers' => $answers
			);
			array_push($questions, $question);
			$question_id++;
		}
		// assign all parameters to our view and display it
		return View::make('game', array('player' => $player_name, 'questions' => $questions));
		
	}
	
	/**
	 * Handles the move of the player with given id.
	 * Checks first, if given player has already played and redirects 
	 * to result page in that case. Otherwise the answers will be assigned
	 * and stored to the database.
	 * 
	 * @param unknown $player_id
	 * @param unknown $game
	 */
	public function handlePlayerMove($player_id, $game) {
		// redirect to result page if given player has already played his move
		if($game->hasPlayerPlayed($player_id)) {
			return Redirect::to('game/'.$game->id.'/result');
		}
		
		$qid = 0;
		// assign the answers for all questions
		foreach($game->gameQuestions as $q) {
			$answerkey = 'answer'.$qid;
			$answer = Input::get($answerkey, '');
			
			$q->setAnswer($player_id, $answer);
			
			$qid++;
		}
		// save modifications to database
		$game->push();
		
		return Redirect::to('game/'.$game->id.'/result');
	}
	/*
	 * Displays the results of given game.
	 */
	public function showResult($game) {
		$questions = array();
		$player1_has_played = $game->hasPlayerPlayed(1);
		$player2_has_played = $game->hasPlayerPlayed(2);
		$player1_rights = 0;
		$player2_rights = 0;
		foreach($game->gameQuestions as $gq) {
			$question_text = $gq->question->qst_text;
			$player1_answer = $gq->answer1;
			$player2_answer = $gq->answer2;
			$right_answer = $gq->question->qst_answer1;
			$player1_color = 'wrong';
			$player2_color = 'wrong';
			if($player1_answer === $right_answer) {
				$player1_color = 'right';
				$player1_rights++;
			}
			if($player2_answer === $right_answer) {
				$player2_color = 'right';
				$player2_rights++;
			}
			$question = array(
				'text' => $question_text,
				'player1Answer' => $player1_answer,
				'player2Answer' => $player2_answer,
				'rightAnswer' => $right_answer,
				'player1Color' => $player1_color,
				'player2Color' => $player2_color,
			);
			array_push($questions, $question);
		}
		return View::make('result', array(
			'player1' => $game->player1name, 
			'player2' => $game->player2name, 
			'player1HasPlayed' => $player1_has_played,
			'player2HasPlayed' => $player2_has_played,
			'player1Rights' => $player1_rights,
			'player2Rights' => $player2_rights,
			'questions' => $questions));;
	}

}
