<?php

 class Game  extends Eloquent {
 	
 	public $incrementing = false;
	protected $fillable = array('player1name', 'player1email', 'player2name', 'player2email');
 	
 	public function gameQuestions() {
 		return $this->hasMany('GameQuestion');
 	}
 	
 	public function hasPlayerPlayed($player_id) {
 		$field = 'answer'.$player_id;
 		return $this->gameQuestions()->whereNull($field)->count() === 0;
 	}
 	
 	public function finished() {
 		return $this->hasPlayerPlayed(1) && $this->hasPlayerPlayed(2);
 	}
 }