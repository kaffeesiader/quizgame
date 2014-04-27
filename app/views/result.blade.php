@extends('layout')

@section('content')
	@if($player1HasPlayed && $player2HasPlayed)
		<p class="gameresult">Score {{ $player1 }}: {{ $player1Rights }} / 5</p>
		<p class="gameresult">Score {{ $player2 }}: {{ $player2Rights }} / 5</p>
	@endif
	@if($player1HasPlayed)
		<h1>Results for {{{ $player1 }}}: </h1>
		<ul>
			@foreach($questions as $q)
				<li>
					<p>{{{ $q['text'] }}}</p>
					<p class="{{ $q['player1Color'] }}">Your answer:  {{{ $q['player1Answer'] }}}</p>
					<p>Right answer: {{{ $q['rightAnswer'] }}}</p>
				</li>
			@endforeach
		</ul>
	@endif
	@if($player2HasPlayed)
		<h1>Results for {{{ $player2 }}}: </h1>
		<ul>
			@foreach($questions as $q)
				<li>
					<p>{{{ $q['text'] }}}</p>
					<p class="{{ $q['player2Color'] }}">Your answer:  {{{ $q['player2Answer'] }}}</p>
					<p>Right answer: {{{ $q['rightAnswer'] }}}</p>
				</li>
			@endforeach
		</ul>
	@endif
@stop
