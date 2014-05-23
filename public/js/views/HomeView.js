//Filename: HomeView.js

define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/home.html'
], function($, _, Backbone, homeTemplate) {
  
	var HomeView = Backbone.View.extend({
		
		el: $("#content"),
		
		render: function() {
			var user = window.user;
			var data = { 
					user: user.username,
					games_lost: user.games_lost,
					games_won: user.games_won,
					games_undecided: user.games_undecided,
					score: user.score
			};
			var compiledTemplate = _.template(homeTemplate, data);
			
			this.$el.html(compiledTemplate);
		},
	
//		events: {
//	        "click #login_btn": "login"
//	    },
	   
	
	});
	
	return HomeView;

});
