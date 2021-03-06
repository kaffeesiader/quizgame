@extends('layout')

@section('content')
	{{ HTML::script('js/jquery-2.1.1.js') }}
	{{ HTML::script('js/home.js') }}
	
	<h1>Welcome {{ $username }} </h1>
	<h3>Current statistics:</h3>
	<div id="player-statistics">
		<p>Games played: <span>{{ $games_played }}</span></p>
		<p>Games won: <span>{{ $games_won }}</span></p>
		<p>Games lost: <span>{{ $games_lost }}</span></p>
		<p>Games undecided: <span>{{ $games_undecided }}</span></p>
		<p><b>Total score: <span>{{ $score }}</span></b></p>
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
	<div id="highscore">
		<table id="highscore-table">
			<caption>Highscore</caption>
			<thead>
			    <tr>
			    	<th>Player name</th>
			    	<th>Score</th>
			        <th>Won</th>
			        <th>Lost</th>
			        <th>Undecided</th>
			    </tr>
		   </thead>
		   <tbody>
		   
		   </tbody>
		</table>
	</div>
@stop