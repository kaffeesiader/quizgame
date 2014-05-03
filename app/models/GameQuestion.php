<?php

class GameQuestion extends Eloquent {
	
	protected $table = 'game_questions';
	public $timestamps = false;
	
	public function getId() {
		return $this->id;
	}
	
	public function getQuestion() {
		return $this->question;
	}
	
	public function setQuestion($qst) {
		$this->question()->associate($qst);
	}
	
	public function setPlayerIndex($index) {
		$this->player_index = $index;
	}
	
	public function getPlayerIndex() {
		return $this->player_index;
	}
	
	public function getGame() {
		return $this->game;
	}
	
	public function answeredCorrect() {
		$correct_answer = $this->question->getCorrectAnswer();
		return $correct_answer === $this->answer;
	}
	
	public function setAnswer($answer) {
		$this->answer = $answer;
		$this->answered_on = date('Y-m-d H:i:s');
		$this->save();
		
		// update score
		$game = $this->getGame();
		$game->updateStatistics($this);
	}
	
	public function getAnswer() {
		return $this->answer;
	}
	/**
	 * Mark this game question instance as already displayed
	 * This will prevent this question to be displayed again
	 */
	public function setDisplayed() {
		$this->displayed_on = date('Y-m-d H:i:s');
		$this->save();
	}
	
	protected function question() {
		return $this->belongsTo('Question', 'question_id', 'qst_id');
	}
	
	protected function game() {
		return $this->belongsTo('Game', 'game_id', 'id');
	}
	
}