<?php
require_once('exceptions/RUsernameAndPasswordLengthException.php');
require_once('exceptions/RPasswordLengthException.php');
require_once('exceptions/RUsernameLengthException.php');
require_once('exceptions/RPasswordMismatchException.php');
require_once('exceptions/RUserExistsException.php');
require_once('exceptions/RInvalidCharactersException.php');

class RegisterModel {

	/**
	 * @var \model\UserDAL $dal
	 */
	private $dal;

	/**
	 * Constructor
	 * @param \model\UserDAL $userDAL
	 */
	public function __construct(UserDAL $userDAL) {
		$this->dal = $userDAL;
	}

	/**
	 * Verifies registration data from user.
	 * 
	 * @param  String $username       Input username.
	 * @param  String $password       Input password.
	 * @param  String $passwordRepeat Input repeated password.
	 *
	 * @throws RUsernameAndPasswordLengthException When $username length is less than 3
	 *         									   AND $password length is less than 6.
	 * @throws RPasswordLengthException 	When $password length is less than 6.
	 * @throws RUsernameLengthException 	When $username length is less than 3.
	 * @throws RPasswordMismatchException	When $password and $passwordRepeat are
	 *         								NOT equal.
	 * @throws RUserExistsException 		When $username already exist in the database.
	 * @throws RInvalidCharactersException 	When $username contains invalid characters.
	 *
	 * @return boolean                 True if registration is successful.
	 */
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