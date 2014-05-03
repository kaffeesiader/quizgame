<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('games', function($t) {
			$t->string('id', 32)->primary();
			$t->integer('player1_id')->unsigned();
			$t->integer('player2_id')->unsigned();
			$t->integer('score_player1')->default(0);
			$t->integer('score_player2')->default(0);
			$t->timestamp('finished_on')->nullable();
			$t->timestamps();
			
			$t->foreign('player1_id')
				->references('id')->on('users')
				->onDelete('restrict');
				
			$t->foreign('player2_id')
				->references('id')->on('users')
				->onDelete('restrict');
		} );
		
		Schema::create('game_questions', function($t) {
			$t->increments('id');
			$t->string('game_id', 32);
			$t->integer('question_id')->unsigned();
			$t->integer('player_index')->unsigned();
			$t->string('answer')->nullable();
			$t->timestamp('displayed_on')->nullable();
			$t->timestamp('answered_on')->nullable();
			
			$t->unique(array('game_id', 'question_id', 'player_index'));
			
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
