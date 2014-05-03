@extends('layout')

@section('content')
	<h1>Player: {{{ $player }}} </h1>
	{{ Form::open(array('url' => 'game/answer/'.$game_question_id, 'class' => 'question-form')) }}
		<p class="question-text">{{{ $question }}}</p>
		<div id="answers">
			<ul>
			@foreach($answers as $answer)
				<li>{{ Form::submit($answer, array('name' => 'submit', 'class' => 'answer-button')) }}</li>
			@endforeach
			</ul>
		</div>
    	
    	<div id="buttons">
    		
    	</div>
    	
	{{ Form::close() }}
@stop