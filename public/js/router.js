// Filename: router.js
define([
  'jquery',
  'underscore',
  'backbone',
  'views/HomeView',
  'views/NewGameView',
  'views/GameView',
  'views/GameResultView'
], function($, _, Backbone, HomeView, NewGameView, GameView, GameResultView) {
	
  var AppRouter = Backbone.Router.extend({
	  
	  routes : {
		  '' : 'home',
		  'game/player/:player_id/:game_id' : 'game',
		  'game/new' : 'newgame',
		  'game/:game_id/result' : 'gameresult',
		  // Default
		  '*actions' : 'home',
	  },
  
  });

  var initialize = function() {
	  
	  console.log("Initializing router");
	  
	  var app_router = new AppRouter;
	  var current_view = null;
	  
	  var switch_view = function(new_view) {
		  if(current_view) {
			  current_view.close();
		  }
		  current_view = new_view;
	  };
	  
	  app_router.on('route:home', function() {
		  console.log("Route: home");
		  var homeView = new HomeView();
		  switch_view(homeView);
		  homeView.render();
	  });
	  
      app_router.on('route:newgame', function() {
    	  console.log("Route: newgame");
    	  var view = new NewGameView();
    	  switch_view(view);
    	  view.render();
      });

      app_router.on('route:game', function(player_id, game_id) {
    	  console.log("Route: game '" + game_id + "' for player" + player_id);
    	  // fetch game from server and render view
    	  var gameView = new GameView();
    	  switch_view(gameView);
    	  gameView.use(game_id, player_id);
      });
      
      app_router.on('route:gameresult', function(game_id) {
    	  console.log("Route: gameresult '" + game_id + "'");
    	  var resultView = new GameResultView();
    	  switch_view(resultView);
    	  resultView.use(game_id);
      });
      
      Backbone.history.start({pushState: false, root: "/quiz/"});
  };
  
  return {
	  initialize: initialize
  };
  
});
