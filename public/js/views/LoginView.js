//Filename: login.js

define([
  // These are path alias that we configured in our bootstrap
  'jquery',     // lib/jquery/jquery
  'underscore', // lib/underscore/underscore
  'backbone',    // lib/backbone/backbone
  'text!templates/login.html'
], function($, _, Backbone, loginTemplate) {
  
	var LoginView = Backbone.View.extend({
		
		el: $("#content"),
		
		render: function() {
			this.$el.html(loginTemplate);
		},
	
		events: {
	        "click #login_btn": "login"
	    },
	    
	    login: function(event) {
	    	event.preventDefault(); // Don't let this button submit the form
	        $('.alert-error').hide(); // Hide any errors on a new submit
	        var target = 'login';
	        console.log('Loggin in... ');
	        var email = $("#input-email").val();
	        
	        $.post(
	    		   target,
	    		   {email: email},
	    		   function(user) {
	    				console.log(user);
	    				window.user = user;
	    				// display the logout link
	    				$('#logout')
	    					.html('logout(' + user.email + ')')
	    					.show();
	    				
	    				window.app.navigate('', { trigger: true });
	    		   },
	    		   'json'
	    	)
	    	.fail(function(data) {
	    		console.log('login failed (' + data.responseText + ')!');
	    		$('.alert-error').html('Login failed: ' + data.responseText);
	    		$('.alert-error').show();
	    	});
	    },
	
	});
	
	return LoginView;

});
