
define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/questionListItemTemplate.html'
], function($, _, Backbone, itemTemplate) {
	
	var QuestionsView = Backbone.View.extend({
		
		initialize: function(questions) {
			this.questions = questions;
		},
		
		el: $('#questions'),
		
		render: function() {
	        var compiledTemplate = _.template(itemTemplate, { questions: this.questions });
			$('#questions').html(compiledTemplate);
		},
	});
	
	return QuestionsView;

});
