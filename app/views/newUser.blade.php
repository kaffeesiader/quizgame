@extends('layout')

@section('content')
	{{ Form::open(array('url' => 'user/new', 'id' => 'user-form')) }}
		<h1>New User</h1>
		<p>Please enter a username for '{{ Auth::user()->email }}'.</p>
		<div>
			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', Input::old('username'), array('placeholder' => 'username')) }}
		</div>

		<div id="buttons">{{ Form::submit('Submit') }}</div>
	{{ Form::close() }}

@stop