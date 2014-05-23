<?php

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

class UserTableSeeder extends Seeder {

	public function run()
	{
// 		DB::table('users')->delete();

// 		User::create(array('email' => 'foo@bar.com'));
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
