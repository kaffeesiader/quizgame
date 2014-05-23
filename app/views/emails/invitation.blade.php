<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Quizgame invitation</h2>

		<p>You're invited to play another round of Quizgame.</p>
		<div id="message-text">
			{{ $message_text }}
		</div>
		<p>Click on the link below and have fun!</p>
		<p><a href="{{ $link }}">{{ $link }}</a></p>
	</body>
</html>
