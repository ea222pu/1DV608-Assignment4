<?php

require_once('model/User.php');

class UserDAL {

	/**
	 * Name of the table where the user data is stored in the database.
	 * @var String $table
	 */
	private $table = "users";

	/**
	 * @var \model\Database $database
	 */
	private $database;

	/**
	 * Constructor
	 * @param \model\Database $db
	 */
	public function __construct(Database $db) {
		$this->database = $db;
	}

	/**
	 * Find user in database using username
	 * 
	 * @param  String $username    		The username being searched for.
	 * @return boolean | \model\User    False if $username does not exist in
	 *                   				database, else User.
	 */
	public function findUserByUsername($username) {
		$sqli = $this->database->connect();
		$stmt = $sqli->prepare("SELECT * FROM " . $this->table);

		if($stmt === FALSE) {
			throw new Exception($sqli->error);
		}
		
		$stmt->execute();
		$stmt->bind_result($dbUsername, $dbPassword);
		while($stmt->fetch()) {
			if($dbUsername === $username) {
				return new User($dbUsername, $dbPassword);
			}
		}
		return false;
	}

	/**
	 * Add user to the database.
	 * 
	 * @param \model\User $user The user to be added to the database
	 */
	public function add(User $user) {
		$sqli = $this->database->connect();
		$stmt = $sqli->prepare("INSERT INTO `users`(`username`, `password`) VALUES (?, ?)");

		if($stmt === FALSE) {
			throw new Exception($sqli->error);
		}

		$username = $user->getUsername();
		$password = $user->getPassword();
		
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
	}
}