// Filename: main.js

// Require.js allows us to configure shortcut alias
// There usage will become more apparent further along in the tutorial.
require.config({
  paths: {
    jquery: 'libs/jquery/jquery',
    cookie: 'libs/jquery/jquery-cookie',
    underscore: 'libs/underscore/underscore',
    backbone: 'libs/backbone/backbone',
    templates: '../templates',
    text: 'libs/require/text'
  }

});

require([

  // Load our app module and pass it to our definition function
  'app',
  'cookie'
  
], function(App){
  // The "app" dependency is passed in as "App"
  App.initialize();
});
