@extends('layout')

@section('content')
	{{ Form::open(array('url' => 'login', 'id' => 'login-form')) }}
		<h1>Authentication</h1>
		<p>Please enter your E-Mail address.</p>

		<div>
			{{ Form::label('email', 'Email Address') }}
			{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
		</div>

		<div id="buttons">{{ Form::submit('Login') }}</div>
	{{ Form::close() }}

@stop