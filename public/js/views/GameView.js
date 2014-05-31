
define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'views/QuestionsView',
  'text!templates/gameTemplate.html'
], function($, _, Backbone, QuestionsView, gameTemplate) {
  
	var GameView = Backbone.View.extend({
		
		initialize: function() {
			this.on('changed', this.render, this);
		},
		
		el: $("#content"),
		
		use: function(game_id, player_id) {
			var that = this;
			var target = "game/" + game_id;
			
			$.get(target, function(game) {
				that.game = game;
				that.player_index = player_id;
				that.trigger('changed');
			}, 'json');
		},
	
		render: function() {
			
			var player = this.player_index == 1 ? this.game.player1 : this.game.player2;
			this.player = player;
			
			var id = this.game.id;
			
			if(player.played) {
				Backbone.history.navigate('game/' + id + '/result', true);
			} else {
		        var compiledTemplate = _.template(gameTemplate, player);
				this.$el.html(compiledTemplate);
				
				var questionList = new QuestionsView(this.game.questions);
				questionList.render();
				
				this.current_question_index = 0;
				$('.unanswered:first').fadeIn('slow');
			}
			
			return this;
		},
		
		events: {
	        "click .unanswered a": "handleAnswer",
	    },
	    
	    handleAnswer: function(e) {
	    	e.preventDefault();
	    	var element = $(e.target);
	    	var answer = element.html();
	    	
	    	console.log("Answer: " + answer);
	    	// find containing question div and hide it...
	    	element.parents('.question-item').removeClass('unanswered').hide();
	    	// store the answer
	    	this.player.answers.push(answer);
	    	// verify if answer was correct and increase score
	    	var qst = this.game.questions[this.current_question_index];
	    	if(answer == qst.correct_answer) {
	    		this.player.score++;
	    	}
	    	
	    	var score_displ = '(' + this.player.score + '/' + this.game.questions.length + ')';
    		$("#score").html(score_displ);
	    	
	    	this.current_question_index++;
	    	// check if all questions are answered
	    	if(this.current_question_index >= this.game.questions.length) {
	    		this.player.played = true;
	    		var target = 'game/player/' + this.player_index + '/' + this.game.id;
	    		var that = this;
		    	$.post(target, 
		    			JSON.stringify({ "answers": this.player.answers, "score": this.player.score, }),
		    			function(response) {
		    				console.log("Game updated!");
		    				// re-render view to trigger move to result page
		    				that.render();
		    			}
		    	);
		    	this.player.played = true;
	    	} else {
	    		$('.unanswered:first').fadeIn('slow');
	    	};
	    },
	   
	});
	
	return GameView;

});
