<?php
/**
 * Only used for database seeding...
 */
class Question extends Eloquent {

	protected $primaryKey = 'pst_id';

	public function getQuestionText() {
		return $this->qst_text;
	}

	public function getAnswers() {
		return array (
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

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('QuestionTableSeeder');
	}

}

class QuestionTableSeeder extends Seeder {
	
	public function run()
	{
		$m = new MongoClient();
		$db = $m->quiz;
		$db->questions->drop();
		$questions = $m->quiz->questions;
		
		foreach (Question::get() as $qst) {
			$id = $qst->qst_id;
			$text = $qst->getQuestionText();
			$answers = $qst->getAnswers();
			$category = $qst->qst_category;
			
			$m_qst = array(
					'_id' => $id,
					'category' => $category,
					'text' => $text,
					'answers' => $answers
			);
			$questions->save($m_qst);
		}
	}
	
}
