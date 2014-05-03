<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;


class User extends Eloquent implements UserInterface, RemindableInterface {
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->username;
	}
	
	public function setName($name) {
		$this->username = $name;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function addGameResult($result) {
		switch ($result) {
			case Game::WON:
				$this->games_won += 1;
				break;
			case Game::LOST:
				$this->games_lost += 1;
				break;
			default:
				$this->games_undecided += 1;
		}
		
		$this->score += $result;
		$this->save();
	}
	
	public function getGamesWon() {
		return $this->games_won;
	}
	
	public function getGamesLost() {
		return $this->games_lost;
	}
	
	public function getGamesUndecided() {
		return $this->games_undecided;
	}
	
	public function getGamesPlayed() {
		return $this->games_won + $this->games_lost + $this->games_undecided;
	}
		
	public function getScore() {
		return $this->score;
	}
	
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	*/
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}
	
	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return '';
	}
	
	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}
	
	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param string $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}
	
	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}
	
	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
}