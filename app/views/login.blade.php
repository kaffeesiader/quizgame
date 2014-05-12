@extends('layout')

@section('content')
	{{ HTML::script('js/jquery-2.1.1.js') }}
	{{ HTML::script('js/jquery.cookie.js') }}
	{{ HTML::script('js/cookie.js') }}

	{{ Form::open(array('url' => 'login', 'id' => 'login-form')) }}
		<h1>Authentication</h1>
		<p>Please enter your E-Mail address.</p>

		<div>
			{{ Form::label('email', 'Email Address') }}
			{{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com')) }}
		</div>

		<div id="login_btn">{{ Form::submit('Login') }}</div>
	{{ Form::close() }}

@stop
