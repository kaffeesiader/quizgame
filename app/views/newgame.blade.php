@extends('layout')

@section('content')
	<h1>Start a new game</h1>
	<p>Please enter name and email for second player. You can also provide a message text. This text will be added to the invitation email.</p>
    {{ Form::model($game, array('route' => 'game.start', 'id' => 'new-game')) }}
    
	    {{ Form::label('email', 'E-Mail') }}
	    {{ Form::email('email', null, array('placeholder' => 'example@email.com')) }}
	    {{ Form::label('messagetext', 'Invitation message') }}
	    {{ Form::textarea('messagetext', null, array('placeholder' => 'Type a message for player 2')); }}
    	
    	<div id="buttons" class="clear">
    		{{ Form::submit('Start game') }}
    	</div>
    	
	{{ Form::close() }}
@stop