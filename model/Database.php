<?php

class Database {

	/**
	 * @var mysqli $connection
	 */
	private $connection;

	/**
	 * Connects to database
	 * 
	 * @return mysqli
	 */
	public function connect() {	
		$this->connection = new mysqli("localhost", "root", "", "register");
		if(mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit;
		}
		return $this->connection;
	}
}