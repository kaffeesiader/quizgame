@extends('layout')

@section('content')
	<h1>Start a new game</h1>
	<p>Please enter required data for two players.</p>
    {{ Form::model($game, array('route' => 'game.start')) }}
    	<div id="player1">
     		<h2>Player 1</h2>
    		{{ Form::label('player1name', 'Username') }}
	    	{{ Form::text('player1name', null, array('placeholder' => 'Name Player1')); }}
	    	{{ Form::label('player1email', 'E-Mail') }}
	    	{{ Form::email('player1email', null, array('placeholder' => 'example@email.com')) }}
    	</div>
    	
    	<div id="player2">
			<h2>Player 2</h2>
    		{{ Form::label('player2name', 'Username') }}
	    	{{ Form::text('player2name', null, array('placeholder' => 'Name Player2')); }}
	    	{{ Form::label('player2email', 'E-Mail') }}
	    	{{ Form::email('player2email', null, array('placeholder' => 'example@email.com')) }}
    	</div>
    	
    	<div id="buttons" class="clear">
    		{{ Form::submit('Start game') }}
    	</div>
    	
	{{ Form::close() }}
@stop