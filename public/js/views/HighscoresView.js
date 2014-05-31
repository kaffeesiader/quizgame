//Filename: HomeView.js

define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'models/HighscoreCollection',
  'text!templates/highscoreTemplate.html',
  'text!templates/highscoreRowTemplate.html'
], function($, _, Backbone, HighscoreCollection, highscoreTemplate, rowTemplate) {
  
	var HomeView = Backbone.View.extend({
		
		initialize: function() {
			this.collection = new HighscoreCollection();
			this.listenTo(this.collection, 'reset', this.onReset);
			this.listenTo(this, 'update', this.update);
			
			var that = this;
			this.timer = setInterval(function() {
				that.trigger('update');
			}, 10000);
			
			this.update();
		},
		
		render: function() {
			this.$el.html(highscoreTemplate);
			return this;
		},
		
		update: function() {
			console.log("Update");
			this.collection.fetch({ reset: true });
		},
		
		onReset: function() {
			console.log("Highscore reset");
			var table = this.$('#highscore-table tbody');
			table.empty();
			
			this.collection.each(function(item) {
				table.append(_.template(rowTemplate, item.attributes));
			});
		},
		
		close: function() {
			console.log("HighscoresView.close");
			clearInterval(this.timer);
			this.stopListening();
		}
		
	});
	
	return HomeView;

});
