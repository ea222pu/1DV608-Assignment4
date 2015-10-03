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
	private $logModel;
	private $message = '';

	/**
	* Constructor
	*
	* @param LoginModel
	*/
	public function __construct(LoginModel $loginModel) {
		$this->logModel = $loginModel;
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

		if($this->logModel->isLoggedIn())
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

	public function loginUser() {
		$username = $_POST[self::$name];
		$password = $_POST[self::$password];
		$persistentLogin = isset($_POST[self::$keep]);
		try {
			$this->logModel->verifyLoginCredentials($username, $password, $persistentLogin);
			if(!$persistentLogin)
				$this->message = 'Welcome';
			else
				$this->message = 'Welcome and you will be remembered';
		} catch(LUsernameMissingException $e) {
			$this->message = 'Username is missing';
		} catch(LPasswordMissingException $e) {
			$this->message = 'Password is missing';
		} catch(LUsernameOrPasswordException $e) {
			$this->message = 'Wrong name or password';
		}
	}

	public function persistentLogin() {
		$cookieName = $_COOKIE[self::$cookieName];
		$cookiePassword = $_COOKIE[self::$cookiePassword];

		try {
			$this->logModel->verifyPersistentLogin($cookieName, $cookiePassword);
			if(!$this->logModel->isLoggedIn()) {
				$this->message = 'Wrong information in coolies';
				$this->deleteCredentialCookies();
			}
			else
				$this->message = 'Welcome back with cookie';
		} catch(LWrongCookieInformationException $e) {
			$this->deleteCredentialCookies();
			$this->message = 'Wrong information in cookies';
		}
	}

	public function registrationMessage() {
		$this->message = 'Registered new user.';
	}

	public function getCookieName() {
		return $_COOKIE[self::$cookieName];
	}

	public function getCookiePassword() {
		return $_COOKIE[self::$cookiePassword];
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
			$this->message = 'Bye bye!';
			return true;
		}
		return false;
	}

	public function isRegisteredCookieSet() {
		return isset($_COOKIE[RegisterView::$successfulRegister]);
	}

	public function deleteRegisteredCookie() {
		setcookie(RegisterView::$successfulRegister, "", time()-3600);
	}

	public function deleteCredentialCookies() {
		if(isset($_COOKIE[self::$cookieName]))
			setcookie(self::$cookieName, "", time()-3600);
		if(isset($_COOKIE[self::$cookiePassword]))
			setcookie(self::$cookiePassword, "", time()-3600);
		$this->rememberName = '';
	}
}