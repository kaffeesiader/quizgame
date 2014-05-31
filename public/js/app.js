// Filename: app.js
define([
  'jquery',
  'underscore',
  'backbone',
  'router', // Request router.js
], function($, _, Backbone, Router){
	
  var initialize = function() {
	  
	  $.ajaxSetup({
			error: function(x, status, error) {
				console.log("Ajax error: " + error);
				// redirect each unauthenticated call to login page.
				alert("Error[" + x.status + "] during ajax request: " + x.responseText);
			},
			// set the base url for all our ajax calls
			contentType: "application/json; charset=utf-8"
	  });
	  // general close method for all views
	  Backbone.View.prototype.close = function() {
		  this.$el.empty();
		  this.stopListening();
		  this.undelegateEvents();
	  };
	  
    // Pass in our Router module and call it's initialize function
    Router.initialize();
  };

  return {
    initialize: initialize
  };
});

