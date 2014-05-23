//Filename: GameView.js

define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/game.html'
], function($, _, Backbone, gameTemplate) {
  
	var GameView = Backbone.View.extend({
		
		el: $("#content"),
		
		render: function() {
			
			var data = { 
					test: "Dies ist ein Test",
			};
			var compiledTemplate = _.template( gameTemplate, data );
			
			this.$el.html(compiledTemplate);
		},
	
//		events: {
//	        "click #login_btn": "login"
//	    },
	   
	
	});
	
	return GameView;

});
