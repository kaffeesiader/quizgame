@extends('layout')

@section('content')
	<h1>Welcome {{ $username }} </h1>
	<h3>Current statistics:</h3>
	<div id="player-statistics">
		<p>Games played: {{ $games_played }}</p>
		<p>Games won: {{ $games_won }}</p>
		<p>Games lost: {{ $games_lost }}</p>
		<p>Games undecided: {{ $games_undecided }}</p>
		<p><b>Total score: {{ $score }}</b></p>
	</div>
	@if(!empty($pending_games))
		<h3>Pending games:</h3>
		<div id="games-list">
			<ul>
				@foreach($pending_games as $game)
				<li>{{ link_to($game['link'], $game['opponent'].'('.$game['start_date'].')') }}</li>
				@endforeach
			</ul>
		</div>
	@else
		<p>There are currently no pending games!</p>
	@endif
@stop