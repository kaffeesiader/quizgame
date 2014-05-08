<?php
/*
 * Represent a Quizgame between two players
 */
 class Game  extends Eloquent {
 	
 	public $incrementing = false;
 	
 	const WON = 3;
 	const LOST = 0;
 	const UNDECIDED = 1;
 	
	/**
	 * Initialize this Game instance with given set of questions
	 * Creates GameQuestion instances for both players.
	 * 
	 * @param GameQuestion[] $questions
	 */
	public function setQuestions($questions) {
		foreach ($questions as $qst) {
			// create a GameQuestion object for each player
			$gq1 = new GameQuestion();
			$gq1->setQuestion($qst);
			$gq1->setPlayerIndex(1);
			$this->questions()->save($gq1);
			
			$gq2 = new GameQuestion();
			$gq2->setQuestion($qst);
			$gq2->setPlayerIndex(2);
			$this->questions()->save($gq2);
		}
	}
	/**
	 * Find the next undisplayed question for player with given index
	 * 
	 * @param $player_index
	 * @return A GameQuestion instance, or NULL, if no question for given player is left
	 */
	public function nextQuestionFor($player_index) {
		$gq = $this->questions()
			->where('player_index', '=', $player_index)
// 			->whereNull('displayed_on')
			->whereNull('answered_on')
			->first();
		
		return $gq;
	}
	/**
	 * Get all undisplayed questions for player with given index
	 * 
	 * @param $player_index
	 * @return An array of GameQuestion instances. Array can be empty.
	 */
	public function questionsFor($player_index) {
		$gq = $this->questions()
			->where('player_index', '=', $player_index)
			->whereNull('answered_on')
			->get();
		
		return $gq;
	}
	
	public function isFinished() {
		return !empty($this->finished_on);
	}
	
	/**
	 * Get the winner of this Game instance.
	 * 
	 * @return The player that has won the game, if the game is finished and the scores of both
	 * 			players are not equal
	 */
	public function getWinner() {
		// we can only declare a winner if the game is finished and
		// one player has a larger score then the other one
		if(!$this->isFinished() || $this->score_player1 == $this->score_player2) {
			return null;
		}
		
		if($this->score_player1 > $this->score_player2) {
			return $this->player1;
		} else {
			return $this->player2;
		}
	}
	/**
	 * Update the statistics of this Game instance with the results of the given
	 * GameQuestion instance. Increases the score of the underlying player and also
	 * checks if all questions are handled.
	 * 
	 * @param GameQuestion $game_question
	 */
	public function updateStatistics($game_question) {
		// do nothing if this game was already marked as finished
		if(!$this->isFinished()) {
			// provide some log output for debugging
			Log::info("Updating game statistics");
			$right_answer = $game_question->getQuestion()->getCorrectAnswer();
			$answer = $game_question->getAnswer();
			Log::info("Correct answer: $right_answer, Answer was: $answer");
			
			// check if given question was answered correct and update score if necessary
			if($game_question->answeredCorrect()) {
				$score_field = 'score_player'.$game_question->getPlayerIndex();
				$current_score = $this->$score_field;
				$new_score = $current_score + 1;
				$this->$score_field = $new_score;
				Log::info("Score of player".$game_question->getPlayerIndex()." was raised from $current_score to $new_score");
			}
			// check if all questions were handled and update overall scores
			Log::info("All questions handled: ".($this->allQuestionsHandled() ? "true" : "false"));
			if($this->allQuestionsHandled()) {
				// mark game as finished.
				$this->finished_on = date('Y-m-d H:i:s');
				$id = $this->getId();
				$finished_on = $this->finished_on;
				
 				Log::info("Game $id finished at $finished_on");
				// look if we have a winner (one player has larger score as the second one...)
				$winner = $this->getWinner();
				
				if($winner) {
					// increase the score of our winner
					$winner->addGameResult(Game::WON);
					$loser = ($this->player1 === $winner) ? $this->player2 : $this->player1;
					$loser->addGameResult(Game::LOST);
				} else {
					// increase the score of both players 
					$this->player1->addGameResult(Game::UNDECIDED);
					$this->player2->addGameResult(Game::UNDECIDED);
				}
				// send email to the two participating players
				// to notify about the end of the game.
				App::make('mail_service')->sendResultNotification($this);
			}
			// save our modifications.
			$this->save();
		}
	}
	
	public function getOpponent($player) {
		return $player->getId() === $this->player1->getId() 
			? $this->player2
			: $this->player1;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return  $this->id;
	}
	
	public function setPlayer1($user) {
		$this->player1()->associate($user);
	}
	
	public function getPlayer1() {
		return $this->player1;
	}
	
	public function setPlayer2($user) {
		$this->player2()->associate($user);
	}
	
	public function getPlayer2() {
		return $this->player2;
	}
	
	public function getPlayer1Score() {
		return $this->score_player1;
	}
	
	public function getPlayer2Score() {
		return $this->score_player2;
	}
	
	public function getResults($player_index) {
		$results = array();
		$game_questions = $this->questions()
							->where('player_index', '=', $player_index)
							->get();
		
		foreach($game_questions as $gq) {
			$question = $gq->getQuestion();
			$qst_text = $question->getQuestionText();
			$right_answer = $question->getCorrectAnswer();
			$answer = $gq->getAnswer();
			$result = $gq->answeredCorrect() ? 'correct' : 'wrong';
			
			$entry = array (
				'question' => $qst_text,
				'right_answer' => $right_answer,
				'player_answer' => $answer,
				'result' => $result
			);
			
			array_push($results, $entry);
		}
		return $results;
	}
	
	// ---------------- Private and protected members ------------------------- //
	
	/**
	 * Check, if there are still unhandled questions for this game
	 *
	 * @return boolean
	 */
	private function allQuestionsHandled() {
		return  !($this->questions()->whereNull('answered_on')->count() > 0);
	}
	
	protected function player1() {
		return $this->belongsTo('User', 'player1_id', 'id');
	}
	
	protected function player2() {
		return $this->belongsTo('User', 'player2_id', 'id');
	}
	
	protected function questions() {
		return $this->hasMany('GameQuestion', 'game_id', 'id');
	}
	
 }