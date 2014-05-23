<?php

/**
 * @author martin
 *
 */
class GameDB {
	
	private static $instance;
	private $db;
	private $mongo_client;
	
	private function __construct() {
		$this->mongo_client = new MongoClient();
		$this->db = $this->mongo_client->quiz;
	}
	
	/**
	 * @return GameDB 
	 */
	public static function getInstance() {
		if(!isset(GameDB::$instance)) {
			GameDB::$instance = new GameDB();
		}
		return GameDB::$instance;
	}
	
	/**
	 * @return MongoCollection
	 */
	public static function games() {
		return GameDB::getInstance()->db->games;
	}
	
	/**
	 * @return MongoCollection
	 */
	public static function players() {
		return GameDB::getInstance()->db->players;
	}
	
	public function getRandomQuestions($count) {
		$db = $this->db;
		$res = $db->command(array('count' => 'questions'));
		$total = $res['n'];
	
		$result = array();
		for($i = 0; $i < $count; $i++) {
			$rand = rand(0, $total - 1);
			$qst = $db->questions->find()->limit(-1)->skip($rand)->getNext();
			array_push($result, $qst);
		}
	
		return $result;
	}
	/**
	 * @return MongoCollection
	 */
	public function getCollection($name) {
		return new MongoCollection($this->db, $name);
	}
}