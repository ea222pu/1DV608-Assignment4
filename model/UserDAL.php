<?php

require_once('model/User.php');

class UserDAL {

	private $table = "users";
	private $database;

	public function __construct(mysqli $db) {
		$this->database = $db;
	}

	public function findUserByUsername($username) {
		$stmt = $this->database->prepare("SELECT * FROM " . $this->table);
		if($stmt === FALSE)
			throw new Exception($this->database->error);
		$stmt->execute();

		$stmt->bind_result($dbUsername, $dbPassword);
		while($stmt->fetch()) {
			if($dbUsername === $username)
				return new User($dbUsername, $dbPassword);
		}
		return false;
	}

	public function add(User $user) {
		$stmt = $this->database->prepare("INSERT INTO `users`(`username`, `password`) VALUES (?, ?)");
		if($stmt === FALSE)
			throw new Exception($this->database->error);
		$username = $user->getUsername();
		$password = $user->getPassword();
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
	}
}