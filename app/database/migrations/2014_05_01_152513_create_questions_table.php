<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create ( 'questions', function ($t) {
			$t->increments ( 'qst_id' );
			$t->string ( 'qst_text' );
			// first answer is always correct (should be shuffled by client)
			$t->string ( 'qst_answer1' );
			$t->string ( 'qst_answer2' );
			$t->string ( 'qst_answer3' );
			$t->string ( 'qst_answer4' );
			$t->string('qst_category');
			$t->timestamps ();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('questions');
	}

}
