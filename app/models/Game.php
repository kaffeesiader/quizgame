<?php
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\InvalidParameterException;
/*
 * Represent a Quizgame between two players
 */
 class Game {
 	
 	const WON = 3;
 	const LOST = 0;
 	const UNDECIDED = 1;
 	
 	private function __construct() {}
 	
 	public static function start($players, $n_qst) {
 		// create a unique id for our new game
 		$game_id = md5(uniqid());
 		$questions = GameDB::getInstance()->getRandomQuestions($n_qst);
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
 		
 		$game['questions'] = array();
 		
 		$question_index = 0;
 		// create a question entry for each question
 		foreach ($questions as $qst) {
 			$game_question = array(
 					'question_index' => $question_index,
 					'text' => $qst['text'],
 					'correct_answer' => $qst['answers'][0],
 					'answers' => $qst['answers']
 			);
 			shuffle($game_question['answers']);
 			
 			array_push($game['questions'], $game_question);
 			$question_index++;
 		}
 		
 		GameDB::games()->save($game);
 		Log::info("Started game with id ".$game_id);
 		
 		return $game_id;
 	}
 	
 	/**
 	 * @param string $game_id
 	 * @param int $player_index
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
 		$options = array('upsert' => true);
 		
 		Log::info(GameDB::players()->update($criteria, $update, $options));
 		Log::info("Statistics of player '$email' updated!");
 	}
 	
 }