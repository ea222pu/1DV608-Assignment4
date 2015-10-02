<?php

require_once("view/iView.php");

class LoginView implements iView {
	public static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $rememberName = '';
	private $lm;
	private $message = '';

	/**
	* Constructor
	*
	* @param LoginModel
	*/
	public function __construct(LoginModel $lm) {
		$this->lm = $lm;
	}

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$response = '';

		if($this->lm->isLoggedIn())
			$response = $this->generateLogoutButtonHTML($this->message);
		else
			$response = $this->generateLoginFormHTML($this->message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>

					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->rememberName . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />

					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	/**
	* Set message to be displayed
	*
	* @param Int
	*/
	public function setMessage($n) {
		switch($n) {
			case 0:
				$this->message = '';
				break;
			case 1:
				$this->message = 'Welcome';
				break;
			case 2:
				$this->message = 'Bye bye!';
				break;
			case 3:
				$this->message = 'Username is missing';
				break;
			case 4:
				$this->message = 'Password is missing';
				break;
			case 5:
				$this->message = 'Wrong name or password';
				break;
			case 6:
				$this->message = 'Welcome and you will be remembered';
				break;
			case 7:
				$this->message = 'Welcome back with cookie';
				break;
			case 8:
				$this->message = 'Wrong information in cookies';
				break;
			case 9:
				$this->message = 'Registered new user.';
				break;
		}
	}

	/**
	* @return String
	*/
	public function getUsername() {
		return $_POST[self::$name];
	}

	/**
	* @return String
	*/
	public function getPassword() {
		return $_POST[self::$password];
	}

	public function getCookieName() {
		return $_COOKIE[self::$cookieName];
	}

	public function getCookiePassword() {
		return $_COOKIE[self::$cookiePassword];
	}

	/**
	* @return boolean
	*/
	public function getPersistentLogin() {
		if(isset($_POST[self::$keep]))
			return true;
		return false;
	}

	public function isCookiesSet() {
		if(isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]))
			return true;
		return false;
	}

	public function isCookieNameSet() {
		return isset($_COOKIE[self::$cookieName]);
	}

	public function setCookieUsername($username) {
		setcookie(self::$cookieName, $username, time()+3600);
	}

	public function setLoginName($username) {
		$this->rememberName = $username;
	}

	/**
	* Set cookies if requested
	* Also for remembering username from login attempt
	*
	* @return boolean
	*/
	public function loginButtonPost() {
		if(isset($_POST[self::$login])) {
			$this->rememberName = $_POST[self::$name];
			if(isset($_POST[self::$keep])) {
				setcookie(self::$cookieName, $_POST[self::$name], time()+3600);
				setcookie(self::$cookiePassword, base64_encode($_POST[self::$password]), time()+3600);
			}
			return true;
		}
		return false;
	}

	/**
	* @return boolean
	*/
	public function logoutButtonPost() {
		if(isset($_POST[self::$logout])) {
			return true;
		}
		return false;
	}

	public function deleteCredentialCookies() {
		if(isset($_COOKIE[self::$cookieName]))
			setcookie(self::$cookieName, "", time()-3600);
		if(isset($_COOKIE[self::$cookiePassword]))
			setcookie(self::$cookiePassword, "", time()-3600);
	}
}