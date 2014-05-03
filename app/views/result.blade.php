@extends('layout')

@section('content')
	
	@if(!$isFinished)
		<p>Still waiting for {{ $opponent }} to complete</p>
		{{ link_to('/', 'Home') }}
	@else
		<div id="gameresult">
			{{ $player1_name }} <span class="player-score">{{ $player1_score }}
			:
			{{ $player2_score }}</span> {{ $player2_name }}
		</div>
		<div id="revenche">
			{{ link_to('/game/'.$game_id.'/revenche', 'Revenche') }}
		</div>
		<h3>Results for {{ $player1_name }}</h3>
		<div class="game-stats">
			@foreach($player1_results as $result)
				<div>
					<p class="question">{{ $result['question'] }}>
					<p>
						Correct answer: <span class="answer-correct">{{ $result['right_answer'] }}</span>&nbsp;
						{{ $player1_name }}'s answer:
						<span class="answer-{{ $result['result'] }}">
							{{ $result['player_answer'] }}
						</span>
					</p>
				</div>
			@endforeach
		</div>
		<h3>Results for {{ $player2_name }}</h3>
		<div class="game-stats">
			@foreach($player2_results as $result)
				<div>
					<p class="question">{{ $result['question'] }}</p>
					<p>Correct answer: <span class="answer-correct">{{ $result['right_answer'] }}</span>&nbsp;
						{{ $player1_name }}'s answer: 
						<span class="answer-{{ $result['result'] }}">
							{{ $result['player_answer'] }}
						</span>
					</p>
				</div>
			@endforeach
		</div>
	@endif
@stop
