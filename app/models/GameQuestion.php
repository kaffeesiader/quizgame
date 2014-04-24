<?php

class GameQuestion extends Eloquent {
	
	protected $table = 'game_questions';
	public $timestamps = false;
	
	public function question() {
		return $this->belongsTo('Question', 'question_id', 'qst_id');
	}
	
	public function game() {
		return $this->belongsTo('Game');
	}
	
	public function setAnswer($player_id, $answer) {
		$fieldName = 'answer'.$player_id;
		$this->$fieldName = $answer;
	}
}