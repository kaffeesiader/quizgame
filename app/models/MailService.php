<?php

/**
 * Does all the E-Mail related stuff
 */
class MailService {
	
	public function sendInvitation($email, $game_id, $message_text) {
		$data = array(
				'link' => 'http://'.$_SERVER['HTTP_HOST'].'/quizgame/game/player2/'.$game_id,
				'message_text' => $message_text
		);
		
		Mail::send('emails.invitation', $data, function ($message) use($email) {
			$message->to($email)->subject('Invitation');
		} );
	}
	
	public function sendResultNotification($game) {
		
	}
	
}