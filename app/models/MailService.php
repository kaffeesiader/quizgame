<?php

/**
 * Does all the E-Mail related stuff
 */
class MailService {
	
	public function sendInvitation($email, $game_id, $message_text) {
		$data = array(
				'link' => 'http://'.$_SERVER['HTTP_HOST'].'/quiz2/#/game/player/2/'.$game_id,
				'message_text' => $message_text
		);
		
		Mail::queue('emails.invitation', $data, function ($message) use($email) {
			//$message->from('quizgame@email.com', 'Quiz Game');
			$message->to($email)->subject('Invitation');
		} );
	}
	
	public function sendResultNotification($game) {
		$data = array(
			'link' => 'http://'.$_SERVER['HTTP_HOST'].'/quiz2/#/game/'.$game['id'].'/result'
		);
		$email_pl1 = $game['player1']['player_email'];
		$email_pl2 = $game['player2']['player_email'];
		Mail::queue('emails.notification', $data, function($message) use($email_pl1) {
			$message->to($email_pl1)->subject('NoSPAM Quiz Game result notification');
		} );
		Mail::queue('emails.notification', $data, function($message) use($email_pl2) {
			$message->to($email_pl2)->subject('NoSPAM Quiz Game result notification');
		} );
	}
	
}
