//Filename: HomeView.js

define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'views/HighscoresView',
  'text!templates/homeTemplate.html'
], function($, _, Backbone, HighscoreView, homeTemplate) {
  
	var HomeView = Backbone.View.extend({
		
		el: '#content',
		
		render: function() {
			
			this.$el.html(homeTemplate);
			this.highscoreView = new HighscoreView({ el: '#highscore'});
			
			$('#pending-games').hide();
			this.highscoreView.render();
		},
		
		close: function() {
			console.log("HomeView.close");
			this.highscoreView.close();
		}
		
	});
	
	return HomeView;

});
