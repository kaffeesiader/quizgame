<!DOCTYPE HTML>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>POWIS - Quizgame</title>
		{{ HTML::style('css/layout.css') }}
	</head>

	<body id="quizgame">
		<div id="wrapper">
			<div id="auth-data">
				@if(Auth::check())
					<p>{{ HTML::linkRoute('logout', 'Logout ('.Auth::user()->email.')') }}</p>
				@endif
			</div>
			<nav id="right">
				<li><a href="/index.php">Home</a></li>
				<li><a href="/team.php">Team</a></li>
				<li><a href="/projects.php">Projects</a></li>
				<li><a href="/wiki/">Wiki</a></li>
				<li><a href="/quizgame/">Quizgame</a></li>
			</nav> <!-- end right -->
			<div id="main">
				<div id="layout-main">
					<header id="header">
						<a href="/quizgame"><h1>QuizGame</h1></a>
					</header>
					<nav id="nav-top">
						<ul>
							<li>{{ link_to('/', 'Home') }}</li>
							<li>{{ HTML::linkRoute('game.new', 'Start new game') }}</li>
						</ul>
					</nav>
					@unless ($errors->isEmpty())
					    <div class="errors">
							<ul>
								@foreach($errors->all() as $error)
							        <li>{{ $error }}</li>
							    @endforeach
							</ul>
						</div>
					@endunless
					<div id="content">
						@yield('content')
					</div> <!-- end content -->
					<footer id="footer">
						<address>Web Information Systems &middot Martin Griesser & Boris Schmiedlehner &middot Copyright 2014</address>
					</footer>
				</div><!-- end layout-main -->
			</div><!-- end main -->
			
		</div> <!-- end wrapper -->
	</body>
</html>
