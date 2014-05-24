/**
 * Really ugly solution!!!
 * We should definitely switch to require.js....
 * 
 */

$().ready(function() {
	
	// define a global error handler for failed ajax requests
	$.ajaxSetup({
//		error: function(x, status, error) {
//			console.log("Ajax error: " + error);
//			// redirect each unauthenticated call to login page.
//			if(x.status == 401) {
//				session.close();
//				Backbone.history.navigate('login', true);
//			} else {
//				console.log("Unknown ajax error");
//				console.log(x);
//				console.log(status);
//				console.log(error);
//			}
//		},
		// set the base url for all our ajax calls
		contentType: "application/json; charset=utf-8"
	});

	var HomeView = Backbone.View.extend({
		
		el: $("#content"),
		
		render: function() {
			var user = session.getUser();
			
			var template = $('#homeTemplate').html();
			var compiledTemplate = _.template(template, { user: user.username });
			
			this.$el.html(compiledTemplate);
			
			$('player-statistics').hide();
			$('#pending-games').hide();
			$('#highscore').hide();
			// load player statistics
			$.get("user/statistics", function(stats) {
				
				var template = $('#playerStatsTemplate').html();
				var html = _.template(template, { stats: stats });
				$('#player-statistics')
					.html(html)
					.show();
				
			}, 'json');
			
			// load list of open games
			$.get("user/opengames", function(games) {
				if(games.length > 0) {
					// remove all existing entries from list
					$('#games-list li').remove();
					
					var itemTemplate = $('#pendingGamesTemplate').html();
					// now create a list item for each entry in the games list 
					for (var i = 0; i < games.length; i++) {
					    // build the html for the table row to insert
						var entry = games[i];
						var link = '/quiz/#/game/' + entry.player_id + '/' + entry.game_id;
						var text = entry.opponent + '(' + entry.start_date.date + ')';
					    var row = _.template(itemTemplate, { link: link, text: text });
					    // append our highscore table with the new row
					    $('#games-list').append(row);
					}
					$('#pending-games').show();
				} else {
					$('#pending-games').hide();
				}
			}, 'json');
			
			// load highscores
			$.get("game/highscore?count=5", function(scores) {
				if(scores.length > 0) {
					// first remove all existing rows from table
					$('#highscore-table tbody tr').remove();
					var rowTemplate = $('#highscoreItemTemplate').html();
					// now create a row for each entry in the highscore list 
					for (var i = 0; i < scores.length; i++) {
					    // build the html for the table row to insert
					    var row = _.template(rowTemplate, { entry: scores[i] });
					    // append our highscore table with the new row
					    $('#highscore-table tbody').append(row);
					}
					// finally display the highscore table
					$("#highscore").show();
				} else {
					// hide the highscore table as there are no entries in there
					$("#highscore").hide();
				}
				
			}, 'json');
		}
	});
	
	var NewGameView = Backbone.View.extend({
		
		el: $("#content"),
		
		render: function() {
			var template = $('#newgameTemplate').html();
			this.$el.html(template);
			$('.errors').hide();
		},

		events: {
	        "click #submit": "startgame"
	    },
	    
	    startgame: function(event) {
	    	event.preventDefault();
	    	
	        $('.errors').hide(); // Hide any errors on a new submit
	      
	        console.log('Starting game... ');
	        var email1 = $("#new_game_email1").val();
	        var name1 = $("#new_game_name1").val();
	        var email2 = $("#new_game_email2").val();
	        var name2 = $("#new_game_name2").val();
	        var message = $('#new_game_messagetext').val();
	        
	        $.post(
	    		   'game/new',
	    		   JSON.stringify({ 
	    			   email1: email1,
	    			   name1: name1,
	    			   email2: email2,
	    			   name2: name2,
	    			   messagetext: message
	    		   }),
	    		   function(result) {
	    			   var id = result.game_id;
	    			   console.log('Game started with id ' + id);
	    			   Backbone.history.navigate('game/player/1/' + id, true);
	    		   },
	    		   'json'
	    	)
	    	.fail(function(data) {
	    		console.log('Error on game start (' + data.responseText + ')!');
	    		$('.errors')
	    			.html('Error starting game: ' + data.responseText)
	    			.show();
	    	});
	        
	    },
	});
	
	var QuestionsView = Backbone.View.extend({
		
		initialize: function(questions) {
			this.questions = questions;
		},
		
		el: $('#questions'),
		
		render: function() {
			var template = $('#questionListTemplate').html();
	        var compiledTemplate = _.template(template, { questions: this.questions });
			$('#questions').html(compiledTemplate);
		},
	});
	
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
				var template = $('#gameTemplate').html();
		        var compiledTemplate = _.template(template, player);
				this.$el.html(compiledTemplate);
				
				var questionListView = new QuestionsView(this.game.questions);
				questionListView.render();
				
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
	
	var ResultsView = Backbone.View.extend({
	
		initialize: function() {
			this.on('changed', this.render, this);
		},
		
		el: $("#content"),
		
		use: function(game_id, player_id) {
			var that = this;
			var target = "game/" + game_id + "/result";
			
			$.get(target, function(game) {
				that.game = game;
				that.player_index = player_id;
				that.trigger('changed');
			}, 'json');
		},
		
		render: function() {
			var template = $('#resultTemplate').html();
			var compiledTemplate = _.template(template, this.game);
			this.$el.html(compiledTemplate);
		},
		
		events: {
			"click #revenche a": "handleRevenche",
		},
		
		handleRevenche: function(e) {
			e.preventDefault();
			console.log("Revenche clicked!");
			var game_id = this.game.id;
			var target = "game/" + game_id + "/revenche";
			$.get(target, function(result) {
				var id = result.game_id;
				console.log("Revenche game started with id " + id);
				Backbone.history.navigate('/game/player/1/' + id, true);
			}, 'json');
		}
		
	});

	var AppRouter = Backbone.Router.extend({
		
		initialize: function() {
			console.log("Initializing router");
			this.gameView = new GameView();
			this.resultView = new ResultsView();
		},
		
	    routes: {
	    	'': 'newgame',
	    	'game/player/:player_id/:game_id': 'game',
	    	'game/new': 'newgame',
	    	'game/:game_id/result': 'gameresult',
	        // Default
	    	'*actions': 'home',
	    },
	    
	    home: function() {
	    	console.log("Route: home");
	    	
	    	var homeView = new HomeView();	
			homeView.render();
	    },
	    
	    game: function(player_id, game_id) {
	    	console.log("Route: game '" + game_id + "' for player" + player_id);
	    	// fetch game from server and render view
	    	this.gameView.use(game_id, player_id);
	    },
	    
	    newgame: function() {
	    	console.log("Route: newgame");
	    	var view = new NewGameView();
	    	view.render();
	    },
	    
	    gameresult: function(game_id) {
	    	console.log("Route: gameresult");
	    	this.resultView.use(game_id);
	    },
	    
	    // this works as some kind of route filter
	    // check first if we are authenticated and redirect to login if necessary
//	    execute: function(callback, args) {
//	    	console.log("Execute fragment: " + Backbone.history.fragment);
//	    	var path = Backbone.history.fragment;
//	    	// redirect all calls to login page, if we are not authenticated
//	    	if(!session.authenticated() && (path != 'login')) {
//	    		console.log("Not authenticated - redirecting to login!");
//	    		Backbone.history.navigate('login', true);
//	    	} else {
//	    		return Backbone.Router.prototype.execute(callback, args);
//	    	}
//	    },
	    
	});
	
	var router = new AppRouter();
	
	Backbone.history.start({pushState: false, root: "/quiz/",});
	
});
