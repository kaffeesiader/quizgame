// Filename: router.js
define([
  'jquery',
  'underscore',
  'backbone',
  'views/LoginView',
  'views/HomeView',
  'views/GameView'
], function($, _, Backbone, LoginView, HomeView, GameView) {
	
  var AppRouter = Backbone.Router.extend({
    routes: {
    	'': 'home',
    	'login': 'login',
    	'logout': 'logout',
    	'game': 'game',
        // Default
    	'*actions': 'defaultAction',
    },
  
  });

  var initialize = function() {
	  
	  var app_router = new AppRouter;
	  
	  app_router.on('route:login', function() {
    	  console.log('performing login!');
    	  var loginView = new LoginView();
    	  loginView.render();
      });
	  
	  app_router.on('route:logout', function() {
		  console.log('logout!');
		  // send logout request to the server...
		  $.get("logout");
		  // hide the logout link
		  $('#logout')
		  	.html('')
		  	.hide();
		  
		  // redirect to login page
		  this.navigate('login', { trigger: true });
	  });
	  
      app_router.on('route:defaultAction', function(actions) {
    	  // We have no matching route, lets just log what the URL was
    	  console.log('No route:', actions);
    	  this.navigate('/', { trigger: true });
      });
      
      app_router.on('route:home', function() {
    	  console.log("route:home");
    	  if(!window.user) {
    		  this.navigate('login', { trigger: true });
    	  } else {
    		  var homeView = new HomeView();
    		  homeView.render();
    	  }
      });
      
      app_router.on('route:game', function() {
    	 var view = new GameView();
    	 view.render();
      });
      
      Backbone.history.start({pushState: true, root: "/quiz/"});
      window.app = app_router;
  };
  
  return {
	  initialize: initialize
  };
  
});
