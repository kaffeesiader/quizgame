
define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/gameResultTemplate.html'
], function($, _, Backbone, gameResultTemplate) {
  
	var GameResultView = Backbone.View.extend({
		
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
			var compiledTemplate = _.template(gameResultTemplate, this.game);
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
		},
		
	});
	
	return GameResultView;

});
