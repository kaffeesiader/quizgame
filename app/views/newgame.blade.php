@extends('layout')

@section('content')
	{{ HTML::script('js/jquery-2.1.1.js') }}
	{{ HTML::script('js/jquery.cookie.js') }}
	{{ HTML::script('js/cookie.js') }}

	<h1>Start a new game</h1>
	<p>Please enter name and email for second player. You can also provide a message text. This text will be added to the invitation email.</p>
    {{ Form::model($game, array('route' => 'game.start', 'id' => 'new-game')) }}
    
	    {{ Form::label('email', 'E-Mail') }}
	    {{ Form::email('email', null, array('placeholder' => 'example@email.com', 'id' => 'new_game_email')) }}
	    {{ Form::label('messagetext', 'Invitation message') }}
	    {{ Form::textarea('messagetext', null, array('placeholder' => 'Type a message for player 2', 'id' => 'new_game_messagetext')); }}
    	
    	<div id="start_game_btn" class="clear">
    		{{ Form::submit('Start game') }}
    	</div>
    	
	{{ Form::close() }}
@stop
