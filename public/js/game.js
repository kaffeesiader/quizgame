/**
 * 
 */

function handle_answer(target, answer, element) {
	$.post(target,
			{ submit : answer },
			function(data) {
				if(data.wascorrect) {
					$(element).addClass("answer-correct");
					console.log('Answer was correct');
				} else {
					$(element).addClass("answer-wrong");
					console.log('Answer was wrong');
				}
				
				console.log(data.wascorrect);
			},
			'json'
		);
}

$().ready(function() {
	// extract the game id from the hidden input field
	var game_id = $("#game_id").attr('value');
	var qst_index = 0;
	var questions = $('.question-item');
	var current_question = questions.first();
	// select all the link tags of class 'answer'
	var answers = $('.answer');
	// add a custom click handler to each answer tag
	answers.click(function(e) {
		// prevent from following link target
		e.preventDefault();
		// trigger our custom event.
		$(this).trigger('answer_clicked');
		console.log($(this).html() + "(" + $(this).attr('href') + ")");
	});
	// add an event handler for our custom 'answer_clicked' event.
	answers.on("answer_clicked", function() {
		var answer = $(this).html();
		var target = $(this).attr('href');
		// send answer information to the server
		handle_answer(target, answer, this);
		// remove event handler to prevent a question to be answered twice...
		$(current_question).find('.answer').off("answer_clicked");
		// display next question or redirect to result page
		qst_index++;
		
		if(qst_index < questions.length) {
			current_question = $(questions[qst_index]);
			current_question.fadeIn('slow');
		} else {
			// wait 2 seconds before move to result page.
			window.setTimeout(function() { location.reload(); }, 2000);
		}
		
	})
	
	current_question.fadeIn('slow');
	
});