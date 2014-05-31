
define([
        
        'backbone'
        
], function(Backbone) {
	
	var HighscoreCollection = Backbone.Collection.extend({
		url: "player/highscores?count=5",
	});
	// Above we have passed in jQuery, Underscore and Backbone
	// They will not be accessible in the global scope
	return HighscoreCollection;
	// What we return here will be used by other modules
});
