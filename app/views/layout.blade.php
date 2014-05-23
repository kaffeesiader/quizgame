<!DOCTYPE HTML>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>POWIS - Quizgame</title>
		{{ HTML::style('css/layout.css') }}
		{{ HTML::script('js/libs/jquery/jquery.js') }}
		{{ HTML::script('js/libs/jquery/jquery-cookie.js') }}
		{{ HTML::script('js/libs/underscore/underscore.js') }}
		{{ HTML::script('js/libs/backbone/backbone.js') }}
		{{ HTML::script('js/home.js') }}
<!-- 		<script data-main="js/main" src="{{ asset('js/libs/require/require.js') }}"></script> -->
	</head>

	<body id="quizgame">
		<div id="wrapper">
			<div id="auth-data">
				<a id="logout" href="/quiz/#/logout"></a>
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
							<li>{{ link_to('/#/', 'Home') }}</li>
							<li>{{ link_to('/#/game/new', 'Start new game') }}</li>
						</ul>
					</nav>
					
					<script type="text/template" id="homeTemplate">

	<h1>Welcome <%= user %> </h1>
	<h3>Current statistics:</h3>
	<div id="player-statistics">
		
	</div>
	<div id="pending-games">
		<h3>Pending games:</h3>
		<ul id="games-list">
		
		</ul>
	</div> <!-- end pending-games -->
	<div id="highscore">
		<table id="highscore-table">
			<caption>Highscore</caption>
			<thead>
			    <tr>
			    	<th>Player name</th>
			    	<th>Score</th>
			        <th>Won</th>
			        <th>Lost</th>
			        <th>Undecided</th>
			    </tr>
		   </thead>
		   <tbody>
		   
		   </tbody>
		</table>
	</div>

					</script>
					
					<script type="text/template" id="playerStatsTemplate">
<p>Games played: <span><%= stats.games_played %></span></p>
<p>Games won: <span><%= stats.games_won %></span></p>
<p>Games lost: <span><%= stats.games_lost %></span></p>
<p>Games undecided: <span><%= stats.games_undecided %></span></p>
<p><b>Total score: <span><%= stats.score %></span></b></p>
					</script>
					
					<script type="text/template" id="pendingGamesTemplate">
<li><a href="<%= link %>"><%= text %></a></li>
					</script>
					
					<script type="text/template" id="highscoreItemTemplate">
<tr>
	<td> <%= entry.name %></td>
	<td> <%= entry.score %></td>
	<td> <%= entry.games_won %></td>
	<td> <%= entry.games_lost %></td>
	<td> <%= entry.games_undecided %></td>
</tr>
					</script>
					
					<script type="text/template" id="newgameTemplate">
<h1>Start a new game</h1>
<div class="errors"></div>
<p>Please enter name and email for second player. You can also provide a message text. This text will be added to the invitation email.</p>
<div id="new-game">
	<label for="email1">E-Mail Player 1</label>
	<input type="email" name="email1" placeholder="example@email.com" id="new_game_email1"></input>
	<label for="name1">Name Player 1</label>
	<input type="text" name="name1" placeholder="player 1" id="new_game_name1"></input>
	<label for="email2">E-Mail Player 2</label>
	<input type="email" name="email2" placeholder="example@email.com" id="new_game_email2"></input>
	<label for="name2">Name Player 2</label>
	<input type="text" name="name2" placeholder="player 2" id="new_game_name2"></input>
	<label for="messagetext">Invitation message</label>
	<textarea name="messagetext" placeholder="Type an invitation message for player2" id="new_game_messagetext"></textarea>
    	
	<div id="start_game_btn" class="clear">
		<button name="submit" value="submit" id="submit">Start game</button>
	</div>
</div>

					</script>
					
					<script type="text/template" id="gameTemplate">
	<h1>Player: <%= player_name %> <span id="score"></span></h1>
	<div id="questions"></div>
					</script>
					
					<script type="text/template" id="questionListTemplate">
	<% _.each(questions, function(question, i) { %>
		<div class="question-item unanswered">
			<p class="question-text"><%= question.text %></p>
			<ul class="question-answers">
			<% _.each(question.answers, function(answer, j){ %>
				<li><a><%= answer %></a>
			<% }); %>
			</ul>
		</div>
	<% }); %>
					</script>
					
					<script type="text/template" id="resultTemplate">
	<% if(!(player1.played && player2.played)) { %>
		<p>Still waiting for second player to complete</p>
	<% } else { %>
		<div id="gameresult">
			<%= player1.player_name %> <span class="player-score"><%= player1.score %>
			:
			<%= player2.score %></span> <%= player2.player_name %>
		</div>
		<div id="revenche">
			<a>Revenche</a>
		</div>
		<h3>Results for <%= player1.player_name %></h3>
		<div class="game-stats">
			<% _.each(questions, function(question, i) { %>
				<div>
					<p class="question"><%= question.text %>>
					<p>
						Correct answer: <span class="answer-correct"><%= question.correct_answer %></span>&nbsp;
						<%= player1.player_name %>'s answer:
						<span class="">
							<%= player1.answers[i] %>
						</span>
					</p>
				</div>
			<% }); %>
		</div>
		<h3>Results for <%= player2.player_name %></h3>
		<div class="game-stats">
			<% _.each(questions, function(question, i) { %>
				<div>
					<p class="question"><%= question.text %>>
					<p>
						Correct answer: <span class="answer-correct"><%= question.correct_answer %></span>&nbsp;
						<%= player2.player_name %>'s answer:
						<span class="">
							<%= player2.answers[i] %>
						</span>
					</p>
				</div>
			<% }); %>
		</div>
	<% }; %>
					</script>
				    
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
