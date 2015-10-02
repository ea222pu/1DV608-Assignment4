<?php

class RegisterModel {

	private $message = 0;
	private $dal;


	public function __construct(UserDAL $userDAL) {
		$this->dal = $userDAL;
	}

	public function verifyRegisterCredentials($username, $password, $passwordRepeat) {
		if(strlen($username) < 3 && strlen($password) < 6) {
			$this->message = 1;
			return false;
		}
		else if(strlen($password) < 6) {
			$this->message = 2;
			return false;
		}
		else if(strlen($username) < 3) {
			$this->message = 3;
			return false;
		}
		else if($password !== $passwordRepeat) {
			$this->message = 4;
			return false;
		}
		else if($this->dal->findUserByUsername($username)) {
			$this->message = 5;
			return false;
		}
		else if(preg_match("/^[0-9A-Za-z_]+$/", $username) == 0) {
			$this->message = 6;
			return false;
		}
		else {
			$user = new User($username, $password);
			$this->dal->add($user);
			$this->message = 9;
			return true;
		}
	}

	public function getMessage() {
		return $this->message;
	}

}