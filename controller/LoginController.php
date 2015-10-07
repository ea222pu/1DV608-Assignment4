<?php

require_once('controller/iController.php');

class LoginController implements iController {

	/**
	 * @var \model\LoginModel $loginModel
	 */
	private $logModel;

	/**
	 * @var \view\loginView $logView
	 */
	private $logView;

	/**
	 * Constructor
	 * @param \view\LoginView   $loginView
	 * @param \model\LoginModel $loginModel
	 */
	public function __construct(LoginView $loginView, LoginModel $loginModel) {
		$this->logView = $loginView;
		$this->logModel = $loginModel;
	}

	/**
	 * Handle user input
	 */
	public function listen() {
		if($this->logView->isRegisteredCookieSet()) {
			$this->logView->setMsgRegistered();
			$this->logView->deleteRegisteredCookie();

			if($this->logView->isCookieNameSet()) {
				$this->logView->setLoginName($this->logView->getCookieName());
				
			}
		}

		// Login
		if($this->logView->loginButtonPost() && !$this->logModel->isLoggedIn()) {
			try {
				$this->login();
			} catch(LUsernameMissingException $e) {
				$this->logView->setMsgUsernameMissing();
			} catch(LPasswordMissingException $e) {
				$this->logView->setMsgPasswordMissing();
			} catch(LUsernameOrPasswordException $e) {
				$this->logView->setMsgUsernameOrPassword();
			}
		}

		// Logout
		else if($this->logView->logoutButtonPost() && $this->logModel->isLoggedIn()) {
			$this->logModel->logout();
			$this->logView->deleteCredentialCookies();
		}

		else if($this->logView->isPost()) {
			$this->logView->redirect();
		}

		else if($this->logView->isCookiesSet()) {
			try {
				$this->cookieLogin();
			} catch(LWrongCookieInformationException $e) {
				$this->logView->deleteCredentialCookies();
				$this->logView->setMsgWrongCookieInfo();
			}
		}

	}

	/**
	 * Called when user wishes to log in. Calls \model\LoginModel 
	 * for verification of input data from user.
	 *
	 * Sets \view\LoginView message
	 */
	private function login() {
		$username = $this->logView->getUsername();
		$password = $this->logView->getPassword();
		$persistentLogin = $this->logView->isSetPersistentLogin();
		
		$this->logModel->verifyLoginCredentials($username, $password, $persistentLogin);
		if(!$persistentLogin) {
			$this->logView->setMsgWelcome();
		}
		else{
			$this->logView->setMsgWelcomeAndRemembered();
		}
	}

	/**
	 * Called when cookies containing login credentials exists.
	 * Calls \model\LoginModel for verification of the data
	 * stored in the cookies.
	 *
	 * Sets \view\LoginView message.
	 */
	private function cookieLogin() {
		$cookieName = $this->logView->getCookieName();
		$cookiePassword = $this->logView->getCookiePassword();
		
		$this->logModel->verifyPersistentLogin($cookieName, $cookiePassword);
		if($this->logModel->isLoggedIn()) {
			$this->logView->setMsgWelcomeWithCookies();
		}
	}

}