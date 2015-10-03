<?php
require_once('exceptions/RUsernameAndPasswordLengthException.php');
require_once('exceptions/RPasswordLengthException.php');
require_once('exceptions/RUsernameLengthException.php');
require_once('exceptions/RPasswordMismatchException.php');
require_once('exceptions/RUserExistsException.php');
require_once('exceptions/RInvalidCharactersException.php');

class RegisterModel {

	private $message = 0;
	private $dal;


	public function __construct(UserDAL $userDAL) {
		$this->dal = $userDAL;
	}

	public function verifyRegisterCredentials($username, $password, $passwordRepeat) {
		if(strlen($username) < 3 && strlen($password) < 6) {
			throw new RUsernameAndPasswordLengthException();
		}
		else if(strlen($password) < 6) {
			throw new RPasswordLengthException();
		}
		else if(strlen($username) < 3) {
			throw new RUsernameLengthException();
		}
		else if($password !== $passwordRepeat) {
			throw new RPasswordMismatchException();
		}
		else if($this->dal->findUserByUsername($username)) {
			throw new RUserExistsException();
		}
		else if(preg_match("/^[0-9A-Za-z_]+$/", $username) == 0) {
			throw new RInvalidCharactersException();
		}
		else {
			$user = new User($username, $password);
			$this->dal->add($user);
			return true;
		}
	}

}