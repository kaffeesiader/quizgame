@extends('layout')

@section('content')
	<h1>Player: {{{ $player }}} </h1>
	{{ Form::open(array('url' => Request::url())) }}

		@foreach($questions as $qst)
			<div class="question_item">
				<p>{{{ $qst['text'] }}}</p>
				@foreach($qst['answers'] as $answer)
					<li>
						{{ Form::radio('answer'.$qst['id'], $answer) }}
						{{ Form::label('', $answer) }}
					</li>
				@endforeach
			</div>
		@endforeach
    	
    	<div>
    		{{ Form::submit('Submit') }}
    	</div>
    	
	{{ Form::close() }}
@stop