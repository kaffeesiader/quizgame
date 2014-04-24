<!DOCTYPE HTML>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>POWIS - Quizgame</title>
		{{ HTML::style('css/layout.css') }}
	</head>

	<body id="quizgame">
		<div id="wrapper">
			
			<header id="header">
				<a href="/quizgame"><h1>Quizgame</h1></a>
			</header>
			
			<nav id="nav-top">
				
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
				<address>Smart Web Solution ltd, Adress 234, 78765 - City  &middot Copyright 2014</address>
			</footer>
			
		</div> <!-- end wrapper -->
	</body>
</html>
