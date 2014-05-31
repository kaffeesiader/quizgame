<!DOCTYPE HTML>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>POWIS - Quizgame</title>
		{{ HTML::style('css/layout.css') }}
		<base href="{{ 'http://'.$_SERVER['HTTP_HOST'].'/quiz/' }}">
 		<script data-main="js/main" src="{{ asset('js/libs/require/require.js') }}"></script>
	</head>

	<body id="quizgame">
		<div id="wrapper">
			<div id="auth-data">
				<a id="logout" href="/quiz/#/logout"></a>
			</div>
		
			<div id="main">
				<div id="layout-main">
					<header id="header">
						<a href="/quizgame"><h1>QuizGame</h1></a>
					</header>
					<nav id="nav-top">
						<ul>
							<li>{{ link_to('/#/', 'Home') }}</li>
							<li>{{ link_to('/#/game/new', 'Start new game') }}</li>
						</ul>
					</nav>
				    
					<div id="content">
						...loading
					</div> <!-- end content -->
					
					<footer id="footer">
						<address>Web Information Systems &middot Martin Griesser & Boris Schmiedlehner &middot Copyright 2014</address>
					</footer>
					
				</div><!-- end layout-main -->
			</div><!-- end main -->
			
		</div> <!-- end wrapper -->
		
	</body>
</html>
