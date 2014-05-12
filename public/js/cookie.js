$(function(){

	var email_adr = $.cookie('your_email_address');
	var new_game_email_adr = $.cookie('friend_email_address');
	var new_game_msg_txt = $.cookie('friend_message');

	$("#email").val(email_adr);
	$("#new_game_email").val(new_game_email_adr);
	$("#new_game_messagetext").val(new_game_msg_txt);

	$("#login_btn").click(function(){
		var adr = $("#email").val();
		$.cookie('your_email_address', adr, {expires: 365});
	});

	$("#email").keypress(function(event){
		if(event.which == 13){
			$("#login_btn").click();
		}
	});

	$("#start_game_btn").click(function(){
		var adr = $("#new_game_email").val();
		var msg = $("#new_game_messagetext").val();
		$.cookie('friend_email_address', adr, {expires: 365});
		$.cookie('friend_message', msg, {expires: 365});
	});


});
