<?php
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\InvalidParameterException;
/*
 * Handles all the game specific requests
 */
 class Game {
 	
 	const WON = 3;
 	const LOST = 0;
 	const UNDECIDED = 1;
 	
 	private function __construct() {}
 	/**
 	 * Start a new game for given set of players.
 	 * $players is expected to be an array, each entry containing the data of one player (name, email)
 	 * Can be useful, if we plan to extend the game to more than two players
 	 * 
 	 * @param array $players
 	 * @param int $n_qst specifies, how many questions the game should contain
 	 * 
 	 * @return string
 	 */
 	public static function start($players, $n_qst) {
 		// create a unique id for our new game
 		$game_id = md5(uniqid());
 		$game = array();
 		$game['id'] = $game_id;
 		
 		$index = 1;
 		// create the players
 		foreach ($players as $player) {
 			$player_key = "player".$index;
 			$player = array(
 					'player_name' => $player['name'],
 					'player_email' => $player['email'],
 					'score' => 0,
 					'played' => false,
 					'answers' => array()
 			);
 			$game[$player_key] = $player;
 			$index++;
 		}
 		// add random questions to our game
 		
 		$questions = GameDB::getInstance()->getRandomQuestions($n_qst);
 		$game['questions'] = array();
 		
 		$question_index = 0;
 		// create a question entry for each question
 		foreach ($questions as $qst) {
 			$game_question = array(
 					'question_index' => $question_index,
 					'text' => $qst['text'],
 					// also keep the correct answer before shuffling
 					'correct_answer' => $qst['answers'][0],
 					'answers' => $qst['answers']
 			);
 			// shuffle the answers
 			shuffle($game_question['answers']);
 			// add the question entry to our game
 			array_push($game['questions'], $game_question);
 			$question_index++;
 		}
 		
 		GameDB::games()->save($game);
 		Log::info("Started game with id ".$game_id);
 		
 		return $game_id;
 	}
 	
 	/**
 	 * Self explaining
 	 * 
 	 * @param string $game_id
 	 * 
 	 * @return Game
 	 */
 	public static function get($game_id) {
 		// get record from games collection
 		$query = array('id' => $game_id,);
 		
 		Log::info("Looking for game with id '".$game_id."'");
 		$game_data = GameDB::games()->findOne($query);
 		
 		if(empty($game_data)) {
 			Log::error("Game: Error loading game with id '$game_id'. No record found");
 			return null;
 		}
 		
 		return $game_data;
 	}
 	/**
 	 * Handle the move of player with given index on game with given id.
 	 * Stores the answers of the player, updates the score and marks the 
 	 * player as already played.
 	 * If both player have played, the game is marked as finished and the 
 	 * player scores are updated
 	 * 
 	 * @param string $game_id
 	 * @param int $player_index
 	 * @param array $data contains two entries: 
 	 * 		'answers' 	array, containing the chosen answers
 	 * 		'score'		the amount of correct answers
 	 */
 	public static function update($game_id, $player_index, $data) {
 		Log::info("Update request for game id ".$game_id);
 		$player = 'player'.$player_index;
 		
 		$answers = $data['answers'];
 		$score = $data['score'];
 			
 		$criteria = array('id' => $game_id);
 		$set = array(
 				$player.'.answers' => $answers,
 				$player.'.score' => $score,
 				$player.'.played' => true
 		);
 		// update the game object
 		Log::info(GameDB::games()->update($criteria, array('$set' => $set)));
 		Log::info("Game with id '$game_id' updated!");
 		// check if game is already finished and update player stats if necessary
 		$game = Game::get($game_id);
 		if($game['player1']['played'] && $game['player2']['played']) {
 			Log::info("Game finished!");
 			// send an email notification to both players
 			App::make('mail_service')->sendResultNotification($game);
 			// mark game as finished
 			GameDB::games()->update($criteria, array('$set' => array('finished' => true)));
 			Game::updateStatistics($game);
 		}
 		
 	}
 	/**
 	 * Retrieve the current highscore table
 	 * 
 	 * @param int $count
 	 * @return multitype:
 	 */
 	public static function getHighscores($count) {
 		
 		$cursor = GameDB::players()
 			->find()
 			->sort(array('score' => -1))
 			->limit($count);
 		
 		$scores = array();
 		foreach ($cursor as $player_score) {
 			array_push($scores, $player_score);
 		}
 		
 		return $scores;
 	}
 	/**
 	 * Helper function to evaluate the game result after game has finished.
 	 * 
 	 * @param array $game
 	 */
 	private static function updateStatistics($game) {
 		
 		$player1 = $game['player1'];
 		$player2 = $game['player2'];
 		$hasWinner = $player1['score'] != $player2['score'];
 		
 		if($hasWinner) {
 			$winner = $player1['score'] > $player2['score'] ? $player1 : $player2;
 			$loser = $player1['score'] < $player2['score'] ? $player1 : $player2;
 			Game::updatePlayerStats($winner, Game::WON);
 			Game::updatePlayerStats($loser, Game::LOST);
 		} else {
 			Game::updatePlayerStats($player1, Game::UNDECIDED);
 			Game::updatePlayerStats($player2, Game::UNDECIDED);
 		}
 	}
 	/**
 	 * Helper function to update the statistics of given player, based on given result
 	 * 
 	 * @param array $player
 	 * @param int $result
 	 */
 	private static function updatePlayerStats($player, $result) {
 		$inc = array();
 		$inc['games_played'] = 1;
 		switch ($result) {
 			case Game::LOST: 
 				$inc['games_lost'] = 1;
 				$inc['games_won'] = 0;
 				$inc['games_undecided'] = 0;
 				$inc['score'] = Game::UNDECIDED;
 				break;
 			case Game::WON:
 				$inc['games_lost'] = 0;
 				$inc['games_won'] = 1;
 				$inc['games_undecided'] = 0;
 				$inc['score'] = Game::WON;
 				break;
 			case Game::UNDECIDED:
 				$inc['games_lost'] = 0;
 				$inc['games_won'] = 0;
 				$inc['games_undecided'] = 1;
 				$inc['score'] = Game::UNDECIDED;
 		}
 		
 		$email = $player['player_email'];
 		$name = $player['player_name'];
 		
 		$criteria = array('email' => $email);
 		$update = array(
 				'$set' => array('name' => $name),
 				'$inc' => $inc
 		);
 		// option 'upsert' means, that a new record will be created, if player with given email
 		// does not exist
 		$options = array('upsert' => true);
 		// update the player data in database
 		Log::info(GameDB::players()->update($criteria, $update, $options));
 		Log::info("Statistics of player '$email' updated!");
 	}
 	
 }