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
			//$message->from('quizgame@email.com', 'Quiz Game');
			$message->to($email)->subject('Invitation');
		} );
	}
	
	public function sendResultNotification($game) {
		$data = array(
			'link' => 'http://'.$_SERVER['HTTP_HOST'].'/quizgame/game/'.$game->getId().'/result'
		);
		$email_pl1 = $game->getPlayer1()->getEmail();
		$email_pl2 = $game->getPlayer2()->getEmail();
		Mail::send('emails.notification', $data, function($message) use($email_pl1) {
			$message->to($email_pl1)->subject('NoSPAM Quiz Game result notification');
		} );
		Mail::send('emails.notification', $data, function($message) use($email_pl2) {
			$message->to($email_pl2)->subject('NoSPAM Quiz Game result notification');
		} );
	}
	
}
