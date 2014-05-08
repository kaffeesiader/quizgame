@extends('layout')

@section('content')
	{{ HTML::script('js/jquery-2.1.1.js') }}
	{{ HTML::script('js/game.js') }}
	
	<h1>Player: {{{ $player }}} </h1>
	<input type="hidden" id="game-id" value="{{ $game_id }}" />
	@foreach($questions as $question)
		<div id="question{{ $question['index'] }}" class="question-item">
			<p class="question-text">{{{ $question['text'] }}}</p>
			<ul class="question-answers">
			@foreach($question['answers'] as $answer)
				<li>{{ link_to('game/answer/'.$question['id'], $answer, array('class' => 'answer')) }}</li>
			@endforeach
			</ul>
		</div>
	@endforeach
@stop