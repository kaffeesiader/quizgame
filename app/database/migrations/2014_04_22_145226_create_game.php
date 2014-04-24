<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function($t) {
			$t->string('id', 32)->primary();
			$t->string('player1name', 50);
			$t->string('player1email', 100);
			$t->string('player2name', 50);
			$t->string('player2email', 100);
			$t->timestamps();
		} );
		
		Schema::create('game_questions', function($t) {
			$t->increments('id');
			$t->string('game_id', 32);
			$t->integer('question_id')->unsigned();
			$t->string('answer1')->nullable();
			$t->string('answer2')->nullable(); 
			
			$t->unique(array('game_id', 'question_id'));
			
			$t->foreign('game_id')
				->references('id')->on('games')
				->onDelete('cascade');
			
			$t->foreign('question_id')
				->references('qst_id')->on('questions')
				->onDelete('restrict');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('game_questions');
		Schema::drop('games');
	}

}
