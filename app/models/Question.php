<?php

class Question extends Eloquent {
	
	protected $primaryKey = 'pst_id';
	
	public function getQuestionText() {
		return $this->qst_text;
	}
	
	public function getAnswers() {
		return array(
				$this->qst_answer1,
				$this->qst_answer2,
				$this->qst_answer3,
				$this->qst_answer4
		);
	}
	
	public function getCorrectAnswer() {
		return $this->qst_answer1;
	}
	
}