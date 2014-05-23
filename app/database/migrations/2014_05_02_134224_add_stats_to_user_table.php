<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatsToUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->integer('games_won')->unsigned()->default(0);
			$table->integer('games_lost')->unsigned()->default(0);
			$table->integer('games_undecided')->unsigned()->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('games_won');
			$table->dropColumn('games_lost');
			$table->dropColumn('games_undecided');
		});
	}

}
