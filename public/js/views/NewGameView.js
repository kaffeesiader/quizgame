/**
 * NewGameView
 */
define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/newGameTemplate.html'
], function($, _, Backbone, newGameTemplate) {
  
	var NewGameView = Backbone.View.extend({
		
		initialize: function() {
			var cookie = $.cookie('former_game_data');
			// data of previous started game is stored in a cookie
			// load data of that cookie or provide an empty object
			if(cookie) {
				this.formerGameData = JSON.parse(cookie);
			} else {
				this.formerGameData = {
					email1: '',
					name1: '',
					email2: '',
					name2: ''
				};
			}
		},
		
		el: $("#content"),
		
		render: function() {
			// pre-fill our inputs with the data of former games
			var compiledTemplate = _.template(newGameTemplate, this.formerGameData);
			this.$el.html(compiledTemplate);
			$('.errors').hide();
		},

		events: {
	        "click #submit": "startgame"
	    },
	    
	    startgame: function(event) {
	    	event.preventDefault();
	    	
	        $('.errors').hide(); // Hide any errors on a new submit
	      
	        console.log('Starting game... ');
	        
	        var game_data = {
	        		email1: $("#new_game_email1").val(),
					name1: $("#new_game_name1").val(),
					email2: $("#new_game_email2").val(),
					name2: $("#new_game_name2").val(),
					messagetext: $('#new_game_messagetext').val()
	        };
	        
	        $.post(
	    		   'game/new',
	    		   JSON.stringify(game_data),
	    		   function(result) {
	    			   var id = result.game_id;
	    			   console.log('Game started with id ' + id);
	    			   // store the game data in cookie, for later usage
	    			   $.cookie('former_game_data', JSON.stringify(game_data), {expires: 365});
	    			   // navigate to game view
	    			   Backbone.history.navigate('game/player/1/' + id, true);
	    		   },
	    		   'json'
	    	);    
	    },
	});
	
	return NewGameView;

});
