@extends('layout')

@section('content')
	<h1>Player: {{{ $player }}} </h1>
	{{ Form::open(array('url' => Request::url())) }}

		@foreach($questions as $qst)
			<div class="question-item">
				<p class="question-text">{{{ $qst['text'] }}}</p>
				@foreach($qst['answers'] as $answer)
					<li>
						{{ Form::radio('answer'.$qst['id'], $answer) }}
						{{ Form::label('', $answer) }}
					</li>
				@endforeach
			</div>
		@endforeach
    	
    	<div id="buttons">
    		{{ Form::submit('Submit') }}
    	</div>
    	
	{{ Form::close() }}
@stop