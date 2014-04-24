@extends('layout')

@section('content')
    {{ Form::model($game, array('route' => 'game.start')) }}
    	<div>
    		{{ Form::label('player1name', 'Player 1') }}
	    	{{ Form::text('player1name', null, array('placeholder' => 'Name Player1')); }}
	    	{{ Form::label('player1email', 'E-Mail') }}
	    	{{ Form::email('player1email', null, array('placeholder' => 'example@email.com')) }}
    	</div>
    	
    	<div>
    		{{ Form::label('player2name', 'Player 2') }}
	    	{{ Form::text('player2name', null, array('placeholder' => 'Name Player2')); }}
	    	{{ Form::label('player2email', 'E-Mail') }}
	    	{{ Form::email('player2email', null, array('placeholder' => 'example@email.com')) }}
    	</div>
    	
    	<div>
    		{{ Form::submit('Start game') }}
    	</div>
    	
	{{ Form::close() }}
@stop